<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'difficulty_id' => ['nullable', 'integer', 'exists:difficulties,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string', 'in:latest,popular'],
        ];
    }
}
