<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Thread;
use App\Models\TemporalUser;

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
        // Arrange: Create data for one thread
        $data = [
            'title' => 'A Test Thread',
            'body' => 'This is the body of the thread.',
            'category' => 'general'
        ];

        // Act: Save the thread
        $response = $this->post(route('threads.store', $data));

        // Assert: Check if a temporal user was created
        $response->assertStatus(302);
        $this->assertDatabaseCount('temporal_users', 1);
    }


    public function test_a_temporal_user_can_create_many_threads(): void
    {
        // Arrange: Create data for two threads  
        $firstData = [
            'title' => 'Thread 1',
            'body' => 'This is the body of the thread.',
            'category' => 'general'
        ];

        $secondData = [
            'title' => 'Thread 2',
            'body' => 'This is the body of the thread.',
            'category' => 'general'
        ];
    
        // Act: Create first thread with no session yet (it'll generate one)
        $response1 = $this->post(route('threads.store'), $firstData);
        $response1->assertStatus(302);
    
        $temporalUserId = TemporalUser::first()->id;
    
        // Act: Simulate session carrying the same temporal user ID 
        $response2 = $this->withSession(['temporal_user_id' => $temporalUserId])
                          ->post(route('threads.store'), $secondData);
        $response2->assertStatus(302);
    
        $temporalUser = TemporalUser::first();

        // Assert: Only one temporal user was created,
        // Two threads were created and Both threads belong to that user
        $this->assertEquals(1, TemporalUser::count());
        $this->assertEquals(2, Thread::count());    
        $this->assertEquals(2, $temporalUser->threads()->count());    
    }

    public function test_a_temporal_user_is_created_when_a_comment_is_published(): void
    {

        $thread = Thread::factory()->create();

        $data = [
            'body' => 'Test body',
        ];
    
        $response = $this->post(route('comments.store', ['id' => $thread->id]), $data);
    
        $response->assertStatus(200);
        $this->assertDatabaseCount('comments', 1);
    }

}
