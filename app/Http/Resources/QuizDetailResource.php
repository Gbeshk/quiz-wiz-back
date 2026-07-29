<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $myAttempt = $this->myAttempt;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'instruction' => $this->instruction,
            'time' => $this->time,
            'difficulty' => DifficultyResource::make($this->whenLoaded('difficulty')),
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
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            'created_at' => $this->created_at,
        ];
    }
}
