<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Thread;
use App\Models\TemporalUser;
use App\Models\Comment;
use Illuminate\Support\Carbon;
use Artisan;

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

        $commentData = [
            'body' => 'Test body',
        ];
    
        $response = $this->post(route('comments.store', ['id' => $thread->id]), $commentData);
    
        $response->assertStatus(200);
        $this->assertDatabaseCount('comments', 1);
    }

    public function test_thread_page_displays_publisher_temporal_username()
    {
        $username = "username_test";
        $tempUser = TemporalUser::factory()->create([
            "assigned_username" => $username
        ]);
        
        $thread = Thread::factory()->for($tempUser)->create();

        $response = $this->get(route('threads.show', ['id' => $thread->id]));

        $response->assertStatus(200);
        $response->assertSeeText($username);
    }

    public function test_thread_page_displays_different_temporal_users_comments_in_order()
    {
        $thread = Thread::factory()->create();

        $firstUsername = "first_user";
        $firstTempUser = TemporalUser::factory()->create([
            "assigned_username" => $firstUsername
        ]);

        $secondUsername = "second_user";
        $secondTempUser = TemporalUser::factory()->create([
            "assigned_username" => $secondUsername
        ]);

        $firstCommentData = [
            'thread_id' => $thread->id,
            'temporal_user_id' => $firstTempUser->id,
            'created_at' => Carbon::now()->subMinute(),
        ];

        $secondCommentData = [
            'thread_id' => $thread->id,
            'temporal_user_id' => $secondTempUser->id,
            'created_at' => Carbon::now(),
        ];

        Comment::factory()->create($firstCommentData);
        Comment::factory()->create($secondCommentData);

        $response = $this->get(route('threads.show', ['id' => $thread->id]));

        $content = $response->getContent();

        $response->assertStatus(200);
        $response->assertSeeText($firstUsername);
        $response->assertSeeText($secondUsername);
        $this->assertTrue(
            strpos($content, $secondUsername) < strpos($content, $firstUsername),
            'Expected "second_user" to appear before "first_user" in the response.'
        );
    }

    public function test_a_temporal_user_without_thread_and_comments_is_deleted_after_two_weeks()
    {
        $tempUser = TemporalUser::factory()->create([
            'created_at' => now()->subDays(15),
        ]);

        Artisan::call('temporal-user:delete-old');

        $this->assertDatabaseMissing('temporal_users', ['id' => $tempUser->id]);
    }

    public function test_a_temporal_user_with_thread_and_comments_cannot_be_deleted_after_two_weeks()
    {
        $tempUser = TemporalUser::factory()->create([
            'created_at' => now()->subDays(15),
        ]);

        $thread = Thread::factory()->create([
            'temporal_user_id' => $tempUser->id,
        ]);

        Comment::factory()->create([
            'temporal_user_id' => $tempUser->id,
            'thread_id' => $thread->id,
        ]);

        Artisan::call('temporal-user:delete-old');

        $this->assertDatabaseHas('temporal_users', ['id' => $tempUser->id]);
    }

}
