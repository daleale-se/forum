<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Thread;

class TemporalUserTest extends TestCase
{

    use RefreshDatabase; 

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
    }

    public function test_a_temporal_user_is_created_when_a_thread_is_published(): void
    {
        // Arrange: Create one thread
        $data = [
            'title' => 'A Test Thread',
            'body' => 'This is the body of the thread.',
            'category' => 'general'
        ];

        // Act: Save the thread and retrieve that thread
        $response = $this->post(route('threads.store', $data));
        $thread = Thread::latest()->first();

        // Assert: Check if a temporal user for the created thread exist
        $response->assertStatus(302);
        $this->assertDatabaseHas('temporal_users', [ 'thread_id' => $thread->id ]);
    }


    // public function test_a_thread_can_be_updated_for_the_op(): void
    // {
        
    // }

    // delete threads for lack of activity (no views)
    // do not delete users until threads are deleted
    // anon users can create many threads

}
