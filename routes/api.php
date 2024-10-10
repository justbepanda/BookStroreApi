<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;
use Illuminate\Support\Facades\Route;

Route::apiResource('authors', AuthorController::class)->except(['destroy']);
Route::apiResource('books', BookController::class)->except(['destroy']);

Route::get('/books/{book}/chapters/{chapter}', [ChapterController::class, 'show']);
Route::post('/books/{book}/chapters', [ChapterController::class, 'store']);
Route::put('/books/{book}/chapters/{chapter}', [ChapterController::class, 'update']);
