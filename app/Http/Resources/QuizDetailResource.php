<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'instruction' => $this->instruction,
            'time' => $this->time,
            'difficulty' => new DifficultyResource($this->whenLoaded('difficulty')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'questions' => $this->whenLoaded('questions', function () {
                return $this->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'text' => $question->text,
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
