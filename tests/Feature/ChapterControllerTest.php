<?php


use App\Models\Author;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChapterControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function testItCanCreateAChapter()
    {
        $book = Book::factory()->create();

        $response = $this->postJson('/api/books/' . $book->id . '/chapters', [
            'book_id' => $book->id,
            'title' => 'Title 2',
            'content' => 'This content is 1',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('chapters', [
            'book_id' => $book->id,
            'title' => 'Title 2',
            'content' => 'This content is 1',
        ]);
    }

    public function testItCanUpdateAChapter()
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create(['book_id' => $book->id]);

        $response = $this->putJson('/api/books/' . $book->id . '/chapters/' . $chapter->id, [
            'title' => 'Updated chapter',
            'content' => 'Updated content',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('chapters', [
            'id' => $chapter->id,
            'title' => 'Updated chapter',
            'content' => 'Updated content',
        ]);
    }


    public function testValidationFailsWhenCreatingAChapterWithoutTitle()
    {
        $book = Book::factory()->create();

        $response = $this->postJson('/api/books/' . $book->id . '/chapters', [
            'content' => 'Content with no title',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function testValidationFailsWhenCreatingAChapterWithoutContent()
    {
        $book = Book::factory()->create();

        $response = $this->postJson('/api/books/' . $book->id . '/chapters', [
            'title' => 'Title with no content',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    }

    public function testItCanRetrieveAChapter()
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create(['book_id' => $book->id]);

        $response = $this->getJson('/api/books/' . $book->id . '/chapters/' . $chapter->id);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $chapter->id,
            'title' => $chapter->title,
            'content' => $chapter->content,
        ]);
    }
}
