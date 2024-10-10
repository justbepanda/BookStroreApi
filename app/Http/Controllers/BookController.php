<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('author')->paginate(10);

        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|min:2|max:100',
            'annotation' => 'nullable|string|max:1000',
            'publication_date' => 'required|date_format:d-m-Y',
        ]);

        $book = Book::create($validated);
        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with('author', 'chapters')->findOrFail($id);
        return response()->json([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author->name,
            'annotation' => $book->annotation,
            'publication_date' => $book->publication_date,
            'chapters' => $book->chapters,
            'character_count' => $book->character_count,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        $validated = $request->validate([
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|min:2|max:100',
            'annotation' => 'nullable|string|max:1000',
            'publication_date' => 'required|date_format:d-m-Y',
        ]);

        $book->update($validated);
        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
