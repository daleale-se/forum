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
        $thread1 = Thread::factory()->create(['title' => 'First Thread']);
        $thread2 = Thread::factory()->create(['title' => 'Second Thread']);

        // Act: visit the homepage
        $response = $this->get('/');

        // Assert: check if the thread titles appear in the response
        $response->assertStatus(200);
        $response->assertSeeText('First Thread');
        $response->assertSeeText('Second Thread');
    }

    public function test_show_page_of_a_thread_with_its_data():void
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

}
