<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskCommentTest extends TestCase
{
    use DatabaseMigrations;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database and authenticate a user
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_add_task_comment()
    {
        $task = Task::factory()->create();

        $data = [
            'task_id' => $task->id,
            'comment' => 'This is a test comment.',
        ];

        $response = $this->postJson(route('task-comment.store'), $data);
        // dd($response->json('data.comment'));
        $response->assertStatus(200);
        $response->assertJsonPath('data.comment', 'This is a test comment.');
    }

    public function test_update_task_comment()
    {
        $task = Task::factory()->create();
        $taskComment = TaskComment::factory()->create(['task_id' => $task->id]);

        $data = [
            'comment' => 'Updated comment.',
        ];

        $response = $this->putJson(route('task-comment.update', $taskComment->id), $data);

        $response->assertStatus(200);
        $response->assertJsonPath('data.comment', 'Updated comment.');
    }

    public function test_delete_task_comment()
    {
        $ytask = Task::factory()->create();
        $taskComment = TaskComment::factory()->create();

        $response = $this->deleteJson(route('task-comment.destroy', $taskComment->id));

        $response->assertStatus(200);
        $this->assertSoftDeleted('task_comments', ['id' => $taskComment->id]);
    }
}
