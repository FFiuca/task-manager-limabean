<?php

namespace Tests\Feature;

use App\Models\Epic;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database and authenticate a user
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'web');
    }

    public function test_index_taks()
    {
        Task::factory(10)->create();

        $response = $this->getJson(route(name: 'task.index'));

        // dump($response->json());
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data.data');
    }

    public function test_add_task()
    {
        $epic = Epic::factory()->create();

        $data = [
            'title' => 'New Task',
            'description' => 'This is a test task description.',
            'status_id' => 1,
            'epic_id' => $epic->id,
            'assign_user_id' => $this->user->id,
            'report_to_user_id' => $this->user->id,
            'priority' => 'medium',
            'due_date' => now()->addDays(7)->toDateTimeString(),
        ];

        $response = $this->postJson(route('task.store'), $data);

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', 'New Task');
    }

    public function test_update_task()
    {
        $epic = Epic::factory()->create();
        $task = Task::factory()->create([
            'epic_id' => $epic->id,
        ]);

        $data = [
            'title' => 'Updated Task Title',
            'description' => 'Updated task description.',
            'status_id' => $task->status_id,
            'epic_id' => $task->epic_id,
            'assign_user_id' => $this->user->id,
            'report_to_user_id' => $this->user->id,
            'priority' => 'high',
            'due_date' => now()->addDays(10)->toDateTimeString(),
        ];

        $response = $this->putJson(route('task.update', $task->id), $data);

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', 'Updated Task Title');
    }

    public function test_destroy_epic()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson(route('task.destroy', $task->id));

        $response->assertStatus(200);
        $this->assertSoftDeleted('tasks', data: ['id' => $task->id]);
    }

}
