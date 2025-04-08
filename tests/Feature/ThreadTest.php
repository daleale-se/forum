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
        $response = $this->post(route('threads.store', $data));

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
        $response = $this->get(route('threads.home'));

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
        $response = $this->get(route('threads.show', ['id' => $thread->id]));

        // Assert: check if the page show all the created thread data
        $response->assertStatus(200);
        $response->assertSeeText('Title of the Thread');
        $response->assertSeeText('This is the body of the thread.');
        $response->assertSeeText('general');
    }

    public function test_it_shows_only_the_threads_filter_by_general_category(): void
    {
        // Arrange: create three threads: two with general category and one with games category
        Thread::factory()->create(['title' => 'General Thread 1', 'category' => 'general']);
        Thread::factory()->create(['title' => 'Gaming Thread', 'category' => 'games']);
        Thread::factory()->create(['title' => 'General Thread 2', 'category' => 'general']);
    
        // Act: visit the home page with the query 'category=general'  
        $response = $this->get(route('threads.index', ['category' => 'general']));

        // Assert: check if the page show two threads
        $response->assertStatus(200);
        $response->assertSee('General Thread 1');
        $response->assertSee('General Thread 2');
        $response->assertDontSee('Gaming Thread');    
    }

    public function test_a_thread_can_be_removed(): void
    {
        // Arrange: create one thread 
        $thread = Thread::factory()->create();

        // Act: Remove the created thread from database  
        $response = $this->delete(route('threads.destroy', ['id' => $thread->id]));

        // Assert: check if the created thread had been eliminated
        $response->assertStatus(200);
        $this->assertDatabaseMissing('thread', ['id' => $thread -> id]);
    }

    public function test_it_shows_a_form_when_we_want_to_create_new_thread(): void
    {
        // Arrange: (no setup needed since we’re just viewing the form page)

        // Act: Make a GET request to the form page
        $response = $this->get(route('threads.form'));

        // Assert: Check that the response is successful and contains the form elements
        $response->assertStatus(200);
        $response->assertSeeText('New thread');
        $response->assertSeeText('Title');
        $response->assertSeeText('Body');
        $response->assertSeeText('Resource');
        $response->assertSeeText('Category');
        $response->assertSeeText('Create thread');
    }

    public function test_a_thread_is_deleted_after_seven_days(): void
    {
        // Arrange: Create a thread 8 days ago
        $thread = Thread::factory()->create([
            'created_at' => now()->subDays(8),
        ]);

        // Act: Run the deletion logic
        Artisan::call('threads:delete-old');

        // Assert: Thread no longer exists
        $this->assertDatabaseMissing('thread', ['id' => $thread->id]);
    }

    public function test_a_thread_can_be_liked(): void
    {
        // Arrange: Create a thread with 0 likes
        $thread = Thread::factory()->create([
            'likes' => 0,
        ]);

        // Act: Hit the route that adds a like
        $response = $this->putJson(route('threads.add_like', ['id' => $thread->id]));

        // Refresh the model to get updated data from DB
        $thread->refresh();

        // Assert: Thread has 1 like now and response is okay
        $response->assertStatus(200);
        $this->assertEquals(1, $thread->likes);
    }

    // public function test_a_thread_can_be_updated_for_the_op(): void
    // {
        
    // }

}
