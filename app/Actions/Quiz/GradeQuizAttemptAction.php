<?php

namespace App\Actions\Quiz;

use App\Models\Quiz;

class GradeQuizAttemptAction
{
    public function execute(Quiz $quiz, array $answers): array
    {
        $questions = $quiz->questions()->with('answers')->get()->keyBy('id');

        $pointsEarned = 0;
        $results = [];

        foreach ($answers as $submitted) {
            $question = $questions->get($submitted['question_id']);
            if (!$question) {
                continue;
            }

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

        return [
            'pointsEarned' => $pointsEarned,
            'results' => $results,
        ];
    }
}
