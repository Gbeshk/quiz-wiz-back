<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['difficulty_id']);
            $table->foreign('difficulty_id')
                ->references('id')->on('difficulties')
                ->restrictOnDelete();
        });

        Schema::table('category_quiz', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['difficulty_id']);
            $table->foreign('difficulty_id')
                ->references('id')->on('difficulties')
                ->cascadeOnDelete();
        });

        Schema::table('category_quiz', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->cascadeOnDelete();
        });
    }
};
