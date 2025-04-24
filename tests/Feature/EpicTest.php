<?php

namespace Tests\Feature;

use App\Models\Epic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EpicTest extends TestCase
{
    use DatabaseMigrations;

    private $user;


    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);

        $this->user = User::first();
        $this->actingAs($this->user, 'web');
    }
    public function test_index_epics()
    {
        Epic::factory(10)->create();

        $response = $this->getJson(route('epic.index'));

        // dump($response->json());
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data.data');
    }

    public function test_store_epic()
    {
        $data = [
            'title' => 'New Epic',
        ];

        $response = $this->postJson(route('epic.store'), $data);

        // dump($response->json());
        $response->assertStatus(200);
        $response->assertJsonPath('data.title', 'New Epic');
        // $this->assertDatabaseHas('epics', ['title' => 'New Epic']);
    }

    public function test_store_epic_validation_error()
    {
        $data = [
            'title' => '', // Invalid title
        ];

        $response = $this->postJson(route('epic.store'), $data);

        $response->assertStatus(400);
        $response->assertJsonPath('message.title', fn($message): bool => isset($message));
        // dd($response->json());
    }

    public function test_show_epic()
    {
        $epic = Epic::factory()->create();

        $response = $this->getJson(route('epic.show', $epic->id));
        // dump($response->json(), $epic->id);

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', $epic->title);
    }

    public function test_update_epic()
    {
        $epic = Epic::factory()->create();

        $data = [
            'title' => 'Updated Epic Title',
        ];

        $response = $this->putJson(route('epic.update', $epic->id), $data);
        // dd($response->json());
        $response->assertStatus(200);
        $response->assertJsonPath('data.title', 'Updated Epic Title');
        $this->assertDatabaseHas('epics', ['id' => $epic->id, 'title' => 'Updated Epic Title']);
    }

    public function test_update_epic_validation_error()
    {
        $epic = Epic::factory()->create();

        $data = [
            'title' => '', // Invalid title
        ];

        $response = $this->putJson(route('epic.update', $epic->id), $data);

        $response->assertStatus(400);
        $response->assertJsonPath('message.title', fn($message): bool => isset($message));
    }

    public function test_destroy_epic()
    {
        $epic = Epic::factory()->create();

        $response = $this->deleteJson(route('epic.destroy', $epic->id));

        $response->assertStatus(200);
        $this->assertSoftDeleted('epics', ['id' => $epic->id]);
    }
}
