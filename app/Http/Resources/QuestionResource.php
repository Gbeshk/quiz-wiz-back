<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $correctCount = $this->whenLoaded('answers', fn() => $this->answers->where('is_correct', true)->count());

        return [
            'id' => $this->id,
            'text' => $this->text,
            'points' => $this->points,
            'type' => $correctCount > 1 ? 'multiple' : 'single',
            'correctAnswersCount' => $correctCount,
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
