<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quiz extends Model
{
    protected $fillable = ['user_id', 'difficulty_id', 'title', 'description', 'image', 'instruction', 'time'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function myAttempt(): HasOne
    {
        return $this->hasOne(QuizAttempt::class)
            ->where('user_id', auth()->id());
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['category_id'] ?? null, function (Builder $q, $categoryId) {
                $q->whereHas('categories', fn(Builder $q2) => $q2->where('categories.id', $categoryId));
            })
            ->when($filters['difficulty_id'] ?? null, function (Builder $q, $difficultyId) {
                $q->where('difficulty_id', $difficultyId);
            })
            ->when($filters['search'] ?? null, function (Builder $q, $search) {
                $q->where('title', 'like', "%{$search}%");
            });
    }

    public function scopeSort(Builder $query, ?string $sort): Builder
    {
        return $sort === 'popular'
            ? $query->orderByDesc('attempts_count')
            : $query->latest();
    }
}
