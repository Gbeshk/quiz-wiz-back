<?php

namespace App\Http\Controllers;

use App\Actions\Quiz\GradeQuizAttemptAction;
use App\Http\Requests\QuizIndexRequest;
use App\Http\Requests\SubmitQuizAttemptRequest;
use App\Http\Resources\QuizDetailResource;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
    public function index(QuizIndexRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $quizzes = Quiz::query()
            ->with(['difficulty', 'categories', 'myAttempt'])
            ->withCount('questions')
            ->withSum('questions', 'points')
            ->withCount('attempts')
            ->filter($validated)
            ->sort($validated['sort'] ?? null)
            ->paginate($validated['per_page'] ?? 15);

        return QuizResource::collection($quizzes);
    }

    public function show(Quiz $quiz): QuizDetailResource
    {
        $quiz->load(['difficulty', 'categories', 'questions.answers', 'myAttempt'])
            ->loadCount('attempts');

        return QuizDetailResource::make($quiz);
    }

    public function submitAttempt(SubmitQuizAttemptRequest $request, Quiz $quiz, GradeQuizAttemptAction $gradeQuizAttempt)
    {
        $validated = $request->validated();

        $user = $request->user();

        if ($user) {
            $existing = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->first();

            if ($existing) {
                return response()->json(['message' => 'Quiz already completed.'], 409);
            }
        }

        $graded = $gradeQuizAttempt->execute($quiz, $validated['answers']);

        $attempt = null;
        if ($user) {
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'points_earned' => $graded['pointsEarned'],
                'time_taken_seconds' => $validated['time_taken_seconds'],
            ]);
        }

        return response()->json([
            'pointsEarned' => $graded['pointsEarned'],
            'correctCount' => collect($graded['results'])->where('is_correct', true)->count(),
            'mistakesCount' => collect($graded['results'])->where('is_correct', false)->count(),
            'saved' => (bool) $attempt,
            'completedAt' => $attempt?->created_at,
        ], 201);
    }
}
