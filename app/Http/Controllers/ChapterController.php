<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function show($bookId, $chapterId)
    {
        $chapter = Chapter::where('book_id', $bookId)->findOrFail($chapterId);
        return response()->json($chapter);
    }

    public function store(Request $request, $bookId)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $chapter = Chapter::create($validatedData);

        $book = $chapter->book;

        if ($book) {
            $book->updateCharacterCount();
        }

        return response()->json($chapter, 201);
    }

    public function update(Request $request, $bookId, $chapterId)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);
        $chapter = Chapter::where('book_id', $bookId)->findOrFail($chapterId);
        $chapter->update($validatedData);

        $book = $chapter->book;

        if ($book) {
            $book->updateCharacterCount();
        }

        return response()->json($chapter);
    }
}
