<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function testCanCreateABook()
    {
        $author = Author::factory()->create();

        $data = [
            'author_id' => $author->id,
            'title' => $this->faker->sentence(3),
            'annotation' => $this->faker->paragraph,
            'publication_date' => $this->faker->date('d-m-Y'),
        ];

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'author_id',
                'title',
                'annotation',
                'publication_date',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('books', [
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'annotation' => $data['annotation'],
            'publication_date' => $data['publication_date'],
        ]);
    }

    public function testCanGetAListOfBooksWithPagination()
    {
        Book::factory()->count(20)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'author_id', 'title', 'annotation', 'publication_date']
                ],
                'first_page_url', 'last_page_url', 'next_page_url', 'prev_page_url',
            ]);
    }

    public function testCanUpdateABook()
    {
        $author = Author::factory()->create();

        $book = Book::factory()->create([
            'author_id' => $author->id,
            'title' => 'Original Title',
            'annotation' => 'Original annotation for the book.',
            'publication_date' => '01-01-2021',
        ]);

        $updatedData = [
            'author_id' => $author->id,
            'title' => 'Updated Title',
            'annotation' => 'Updated annotation for the book.',
            'publication_date' => '12-12-2022',
        ];

        $response = $this->putJson("/api/books/{$book->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $book->id,
                'author_id' => $author->id,
                'title' => 'Updated Title',
                'annotation' => 'Updated annotation for the book.',
                'publication_date' => '12-12-2022',
            ]);

        $this->assertDatabaseHas('books', $updatedData);
    }

    public function testCanShowASingleBook()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'annotation',
                'publication_date',
                'chapters',
                'character_count',
            ]);
    }
}
