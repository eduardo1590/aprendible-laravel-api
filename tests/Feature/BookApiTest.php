<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

class BookApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_can_get_all_books()
    {
        $books = Book::factory(5)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);
    }

    public function test_can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    public function test_can_create_books()
    {
        $this->postJson(route('books.store'), [])->assertJsonvalidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'My new Book'
        ])->assertJsonFragment([
            'title' => 'My new Book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My new Book'
        ]);
    }

    public function test_can_update_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])->assertJsonvalidationErrorFor('title');

        $response = $this->patchJson(route('books.update', $book),[
            'title' => 'Edited Book'
        ])->assertJsonFragment([
            'title' => 'Edited Book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Edited Book'
        ]);
    }

    public function test_can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }

}
