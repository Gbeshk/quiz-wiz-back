<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $myAttempt = $this->attempts?->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'instruction' => $this->instruction,
            'time' => $this->time,
            'difficulty' => $this->whenLoaded('difficulty', fn() => $this->difficulty->name),
            'difficultyColor' => $this->whenLoaded('difficulty', fn() => $this->difficulty->color),
            'pointsTotal' => $this->whenLoaded('questions', fn() => $this->questions->sum('points')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'timesPlayed' => $this->attempts_count ?? 0,
            'isCompleted' => (bool) $myAttempt,
            'pointsEarned' => $myAttempt?->points_earned ?? 0,
            'myAttempt' => $myAttempt ? [
                'pointsEarned' => $myAttempt->points_earned,
                'timeTakenSeconds' => $myAttempt->time_taken_seconds,
                'completedAt' => $myAttempt->created_at,
            ] : null,
            'questions' => $this->whenLoaded('questions', function () {
                return $this->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'text' => $question->text,
                        'points' => $question->points,
                        'answers' => $question->answers->map(function ($answer) {
                            return [
                                'id' => $answer->id,
                                'text' => $answer->text,
                            ];
                        }),
                    ];
                });
            }),
            'created_at' => $this->created_at,
        ];
    }
}
