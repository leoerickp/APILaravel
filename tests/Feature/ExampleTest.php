<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Tests\TestCase;


class BooksTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    function can_get_all_books()
    {
        $book=Book::factory(4)->create();
        $response=$this->getJson(route('books.index'))
        ->assertJsonFragment([
            'title'=>$books[0]->title
        ])->assertJsonFragment([
            'title'=>$books[1]->title
        ]);
    }
    /** @test */
    function can_get_one_book()
    {
        $book=Book::factory()->create();
        $response=$this->getJson(route('books.show',$book))
        ->assertJsonFragment([
            'title'=>$book->title
        ]);
    }
    /** @test */
    function can_create_book()
    {
        $this->postJson(route('books.store',[]))
        ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store',[
            'title'=>'My new book'
        ]))->assertJsonFragment([
            'title'=>'My new book'
        ]);

        $this->assertDatabasesHas('My new book');
    }
    /** @test */
    function can_update_book()
    {
        $this->patchJson(route('books.update',[]))
        ->assertJsonValidationErrorFor('title');
        $book=Book::factory()->create();
        $this->patchJson(route('books.update',$book),[
            'title'=>'Edited book'
        ])->assertJsonFragment([
            'title'=>'Edited book'
        ]);

        $this->assertDatabasesHas('Edited book');
    }

    /** @test */
    function can_delete_book()
    {
        
        $book=Book::factory()->create();

        $this->deleteJson(route('books.destroy',$book))
        ->assertNoContent();

        $this->assertDatabasesCount('books',0);
    }
}
