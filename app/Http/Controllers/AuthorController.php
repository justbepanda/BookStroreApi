<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authors = Author::withCount('books')
            ->orderBy('books_count', 'desc')
            ->paginate(15);

        return response()->json($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:2|max:40',
            'info' => 'nullable|string|max:1000',
            'birth_date' => 'nullable|string|date_format:d-m-Y',
        ]);

        $author = Author::create($validatedData);

        return response()->json($author, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::with('books')->findOrFail($id);

        $booksCount = $author->getBooksCountAttribute();

        return response()->json([
            'id' => $author->id,
            'name' => $author->name,
            'info' => $author->info,
            'birth_date' => $author->birth_date,
            'books_count' => $booksCount,
            'books' => $author->books
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $author = Author::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:40',
            'info' => 'nullable|string|max:1000',
            'birth_date' => 'nullable|date_format:d-m-Y',
        ]);

        $author->update($validated);
        return response()->json($author);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
