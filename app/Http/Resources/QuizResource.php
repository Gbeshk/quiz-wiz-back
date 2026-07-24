<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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
            'questions_count' => $this->whenCounted('questions'),
            'pointsTotal' => $this->questions_sum_points ?? 0,
            'difficulty' => $this->whenLoaded('difficulty', fn() => $this->difficulty->name),
            'difficultyColor' => $this->whenLoaded('difficulty', fn() => $this->difficulty->color),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'timesPlayed' => $this->attempts_count ?? 0,
            'isCompleted' => (bool) $myAttempt,
            'pointsEarned' => $myAttempt?->points_earned ?? 0,
            'completedDate' => $myAttempt?->created_at,
            'created_at' => $this->created_at,
        ];
    }
}
