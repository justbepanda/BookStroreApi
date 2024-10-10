<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function testItCanCreateAnAuthor()
    {
        $data = [
            'name' => $this->faker->name,
            'info' => $this->faker->text(200),
            'birth_date' => $this->faker->date('d-m-Y'),
        ];

        $response = $this->postJson('/api/authors', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'info', 'birth_date']);

        $this->assertDatabaseHas('authors', [
            'name' => $data['name'],
            'info' => $data['info'],
            'birth_date' => $data['birth_date'],
        ]);
    }

    public function testItCanUpdateAnAuthor()
    {
        $author = Author::factory()->create();

        $newData = [
            'name' => $this->faker->name,
            'info' => $this->faker->text(200),
            'birth_date' => $this->faker->date('d-m-Y'),
        ];

        $response = $this->putJson("/api/authors/{$author->id}", $newData);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'info', 'birth_date']);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => $newData['name'],
            'info' => $newData['info'],
            'birth_date' => $newData['birth_date'],
        ]);
    }

    public function testItCanGetPaginatedAuthors()
    {
        Author::factory()->count(20)->create()->each(function ($author) {
            Book::factory()->count(rand(1, 5))->create(['author_id' => $author->id]);
        });

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'info',
                        'birth_date',
                        'books_count',
                    ]
                ],
                'first_page_url',
                'last_page_url',
                'next_page_url',
                'prev_page_url',
                'per_page',
                'total'
            ])
            ->assertJsonCount(15, 'data'); // Проверка, что на странице не больше 15 авторов

        $authors = Author::withCount('books')->orderBy('books_count', 'desc')->take(15)->get();
        $responseAuthors = collect($response->json('data'));

        $this->assertEquals($authors->pluck('id')->toArray(), $responseAuthors->pluck('id')->toArray());
    }

    public function testItCanGetASingleAuthor()
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(3)->create(['author_id' => $author->id]);

        $response = $this->getJson("/api/authors/{$author->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'info',
                'birth_date',
                'books_count',
                'books' => [
                    '*' => [
                        'id',
                        'title',
                        'annotation',
                        'publication_date',
                        'author_id',
                    ]
                ]
            ]);
    }

}
