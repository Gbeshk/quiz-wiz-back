<?php

namespace App\Http\Controllers;

use App\Http\Resources\DifficultyResource;
use App\Models\Difficulty;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DifficultyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DifficultyResource::collection(Difficulty::query()->orderBy('id')->get());
    }
}
