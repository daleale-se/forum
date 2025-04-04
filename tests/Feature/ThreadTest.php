<?php

namespace Tests\Feature;

use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Thread;

class ThreadTest extends TestCase
{

    use RefreshDatabase; 

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');
    }

    public function test_a_thread_can_be_created(): void  
    {
        // Arrange: initialize a new thread 
        $data = [
            'title' => 'A Test Thread',
            'body' => 'This is the body of the thread.',
            'category' => 'general'
        ];

        // Act: create a thread in the database
        $response = $this->post('/threads', $data);

        // Assert: check if the database has the created thread
        $response->assertStatus(302);
        $this->assertDatabaseHas('thread', ['title' => 'A Test Thread']);
    }

    public function test_it_shows_all_threads_on_the_homepage(): void
    {
        // Arrange: create some threads in the database
        Thread::factory()->create(['title' => 'First Thread']);
        Thread::factory()->create(['title' => 'Second Thread']);

        // Act: visit the homepage
        $response = $this->get('/');

        // Assert: check if the thread titles appear in the response
        $response->assertStatus(200);
        $response->assertSeeText('First Thread');
        $response->assertSeeText('Second Thread');
    }

    public function test_it_shows_the_page_of_a_thread_with_its_data():void
    {
        // Arrange: create one thread 
        $data = [
            'title' => 'Title of the Thread',
            'body' => 'This is the body of the thread.',
            'category' => 'general'
        ];
        $thread = Thread::factory()->create($data);

        // Act: visit the created thread page 
        $response = $this->get("/threads/{$thread -> id}");

        // Assert: check if the page show all the created thread data
        $response->assertStatus(200);
        $response->assertSeeText('Title of the Thread');
        $response->assertSeeText('This is the body of the thread.');
        $response->assertSeeText('general');
    }

    public function test_it_shows_only_the_threads_filter_by_general_category(): void
    {
        Thread::factory()->create(['title' => 'General Thread 1', 'category' => 'general']);
        Thread::factory()->create(['title' => 'Gaming Thread', 'category' => 'games']);
        Thread::factory()->create(['title' => 'General Thread 2', 'category' => 'general']);
    
        $response = $this->get('/threads/?category=general');

        $response->assertStatus(200);
        $response->assertSee('General Thread 1');
        $response->assertSee('General Thread 2');
        $response->assertDontSee('Gaming Thread');    
    }

    public function test_remove_a_existing_thread(): void
    {
        $thread = Thread::factory()->create();

        $response = $this->delete("/threads/{$thread -> id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('thread', ['id' => $thread -> id]);
    }

}
