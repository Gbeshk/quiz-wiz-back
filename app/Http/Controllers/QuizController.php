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

        $query = Quiz::query()
            ->with(['difficulty', 'categories'])
            ->withCount('questions')
            ->withSum('questions', 'points')
            ->withCount('attempts');

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
        $quiz->load(['difficulty', 'categories', 'questions.answers'])
            ->loadCount('attempts');

        if ($request->user()) {
            $quiz->load(['attempts' => fn($q) => $q->where('user_id', $request->user()->id)]);
        }

        return new QuizDetailResource($quiz);
    }

    public function submitAttempt(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'points_earned' => ['required', 'integer', 'min:0'],
            'time_taken_seconds' => ['required', 'integer', 'min:0'],
        ]);

        $existing = QuizAttempt::where('user_id', $request->user()->id)
            ->where('quiz_id', $quiz->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Quiz already completed.'], 409);
        }

        $attempt = QuizAttempt::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $quiz->id,
            'points_earned' => $validated['points_earned'],
            'time_taken_seconds' => $validated['time_taken_seconds'],
        ]);

        return response()->json($attempt, 201);
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
