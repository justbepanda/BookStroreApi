<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Получаем все существующие книги
        $books = Book::all();

        // Проверяем, есть ли книги
        if ($books->isEmpty()) {
            $this->command->info('Books not found');
            return;
        }

        foreach ($books as $book) {
            // Создаем несколько глав для каждой книги
            Chapter::create([
                'book_id' => $book->id,
                'title' => 'Chapter 1: Introduction to ' . $book->title,
                'content' => 'Content of Chapter 1 of the book ' . $book->title,
                'character_count' => strlen('Content of Chapter 1 of the book ' . $book->title),
            ]);
            Chapter::create([
                'book_id' => $book->id,
                'title' => 'Chapter 2: Basics of ' . $book->title,
                'content' => 'Content of Chapter 2 of the book ' . $book->title,
                'character_count' => strlen('Content of Chapter 2 of the book ' . $book->title),
            ]);

            $totalCharacterCount = Chapter::where('book_id', $book->id)->sum('character_count');
            $book->character_count = $totalCharacterCount;
            $book->save();
        }
    }
}
