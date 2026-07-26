<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\DifficultyResource;
use App\Http\Resources\QuizDetailResource;
use App\Http\Resources\QuizResource;
use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'difficulty_id' => ['nullable', 'integer', 'exists:difficulties,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $userId = $request->user()?->id;
        $query = Quiz::query()
            ->with(['difficulty', 'categories'])
            ->withCount('questions')
            ->withSum('questions', 'points')
            ->withCount('attempts')
            ->with(['attempts' => function ($q) use ($userId) {
                $userId
                    ? $q->where('user_id', $userId)
                    : $q->whereRaw('1 = 0');
            }]);

        if ($request->user()) {
            $query->with(['attempts' => fn($q) => $q->where('user_id', $request->user()->id)]);
        }

        if (!empty($validated['category_id'])) {
            $query->whereHas('categories', function ($q) use ($validated) {
                $q->where('categories.id', $validated['category_id']);
            });
        }

        if (!empty($validated['difficulty_id'])) {
            $query->where('difficulty_id', $validated['difficulty_id']);
        }

        if (!empty($validated['search'])) {
            $query->where('title', 'like', '%' . $validated['search'] . '%');
        }

        $perPage = $validated['per_page'] ?? 15;

        $quizzes = $query->latest()->paginate($perPage);

        return QuizResource::collection($quizzes);
    }

    public function show(Request $request, Quiz $quiz): QuizDetailResource
    {
        $userId = $request->user()?->id;

        $quiz->load(['difficulty', 'categories', 'questions.answers'])
            ->loadCount('attempts')
            ->load(['attempts' => function ($q) use ($userId) {
                $userId
                    ? $q->where('user_id', $userId)
                    : $q->whereRaw('1 = 0');
            }]);

        return new QuizDetailResource($quiz);
    }
    public function submitAttempt(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'time_taken_seconds' => ['required', 'integer', 'min:0'],
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'integer', 'exists:questions,id'],
            'answers.*.answer_ids' => ['array'],
            'answers.*.answer_ids.*' => ['integer', 'exists:answers,id'],
        ]);

        $user = $request->user();

        if ($user) {
            $existing = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->first();

            if ($existing) {
                return response()->json(['message' => 'Quiz already completed.'], 409);
            }
        }

        $questions = $quiz->questions()->with('answers')->get()->keyBy('id');

        $pointsEarned = 0;
        $results = [];

        foreach ($validated['answers'] as $submitted) {
            $question = $questions->get($submitted['question_id']);
            if (!$question) continue;

            $correctIds = $question->answers->where('is_correct', true)->pluck('id')->sort()->values();
            $selectedIds = collect($submitted['answer_ids'] ?? [])->sort()->values();

            $isCorrect = $correctIds->isNotEmpty()
                && $correctIds->count() === $selectedIds->count()
                && $correctIds->diff($selectedIds)->isEmpty();

            if ($isCorrect) {
                $pointsEarned += $question->points;
            }

            $results[] = [
                'question_id' => $question->id,
                'is_correct' => $isCorrect,
            ];
        }

        $attempt = null;
        if ($user) {
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'points_earned' => $pointsEarned,
                'time_taken_seconds' => $validated['time_taken_seconds'],
            ]);
        }

        return response()->json([
            'pointsEarned' => $pointsEarned,
            'correctCount' => collect($results)->where('is_correct', true)->count(),
            'mistakesCount' => collect($results)->where('is_correct', false)->count(),
            'saved' => (bool) $attempt,
            'completedAt' => $attempt?->created_at,
        ], 201);
    }
    public function categories(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::query()->orderBy('name')->get());
    }

    public function difficulties(): AnonymousResourceCollection
    {
        return DifficultyResource::collection(Difficulty::query()->orderBy('id')->get());
    }
}
