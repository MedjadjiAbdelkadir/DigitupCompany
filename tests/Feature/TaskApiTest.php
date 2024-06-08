<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_create_new_task()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tasks', [
            'user_id' => $this->user->id,
            'titre' => 'New Task',
            'description' => 'Task Description',
            'statut' => 'Waiting',
            'due_date' => '2024-06-25',
        ]);
        $response->assertStatus(201)->assertJson([
            'data' => [
                'user_id' => $this->user->id,
                'titre' => 'New Task',
                'description' => 'Task Description',
                'statut' => 'Waiting',
                'due_date' => '2024-06-25',
            ],
        ]);
    }
    public function test_admin_can_create_new_task()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/tasks', [
            'user_id' => $this->admin->id,
            'titre' => 'New Task',
            'description' => 'Task Description',
            'statut' => 'Waiting',
            'due_date' => '2024-06-25',
        ]);
        $response->assertStatus(201)->assertJson([
            'data' => [
                'user_id' => $this->admin->id,
                'titre' => 'New Task',
                'description' => 'Task Description',
                'statut' => 'Waiting',
                'due_date' => '2024-06-25',
            ],
        ]);
        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'titre',
                'description',
                'statut',
                'due_date',
            ],
        ]);
    }
    public function test_user_or_admin_create_new_task_validation_errors(){
        $response = $this->actingAs($this->user)->postJson('/api/tasks', [
            'user_id' => $this->user->id,
            'description' => 'Task Description',
            'statut' => 'Waiting',
            'due_date' => '2024-04-25',
        ]);
        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['titre', 'due_date']);
    }
    public function test_user_can_see_the_task_that_belongs_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'titre',
                'description',
                'statut',
                'due_date',
            ],
        ]);
    }

    public function test_user_cannot_see_task_not_belong_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->user)->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(403);
    }


    public function test_admin_can_see_all_specific_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $task_admin = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->admin)->getJson("/api/tasks/{$task_admin->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'titre',
                'description',
                'statut',
                'due_date',
            ],
        ]);
    }

    public function test_user_can_update_the_task_that_belongs_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'titre' => 'Updated Task',
            'description' => 'Updated Description',
            'statut' => 'Waiting',
            'due_date' => '2024-06-25',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'titre',
                'description',
                'statut',
                'due_date',
            ],
        ]);
    }


    public function test_user_cannot_update_task_not_belong_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'titre' => 'Updated Task',
            'description' => 'Updated Description',
            'statut' => 'Waiting',
            'due_date' => '2024-06-25',
        ]);
        $response->assertStatus(403);
    }
    public function test_admin_can_see_update_all_specific_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $task_admin = Task::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->putJson("/api/tasks/{$task->id}", [
            'titre' => 'Updated Task User',
            'description' => 'Updated Description User',
            'statut' => 'Waiting',
            'due_date' => '2024-06-25',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'titre',
                'description',
                'statut',
                'due_date',
            ],
        ]);

        $response = $this->actingAs($this->admin)->putJson("/api/tasks/{$task_admin->id}", [
            'titre' => 'Updated Task User',
            'description' => 'Updated Description User',
            'statut' => 'Waiting',
            'due_date' => '2024-06-25',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'titre',
                'description',
                'statut',
                'due_date',
            ],
        ]);
    }


    public function test_user_can_delete_the_task_that_belongs_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(200);
    }
    public function test_admin_can_delete_your_specific_task()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->admin)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(200);
    }
    public function test_admin_can_delete_all_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->admin)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(200);
    }

    public function test_user_cannot_delete_task_not_belong_to_him()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(403);
    }
    public function test_user_cannot_restore_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");
        $response = $this->actingAs($this->user)->get("/api/tasks/deleted");
        $response->assertStatus(403);
    }
    public function test_admin_cann_restore_task()
    {
        $task = Task::factory()->create(['user_id' => $this->admin->id]);
        $response = $this->actingAs($this->admin)->deleteJson("/api/tasks/{$task->id}");
        $response = $this->actingAs($this->admin)->get("/api/taskss/deleted");
        $response->assertStatus(200);
    }


    public function test_user_can_see_paginate_tasks_without_trashed()
    {
        Task::factory(20)->create([
            'user_id' => $this->admin->id
        ]);
        $response = $this->actingAs($this->admin)->getJson('/api/tasks');
        $response->assertStatus(200)->assertJsonCount(10, 'data.data');
    }

    public function test_admin_can_see_paginate_tasks_with_trashed()
    {
        Task::factory(20)->create([
            'user_id' => $this->admin->id
        ]);
        $lastPost = Task::latest()->first();
        $response = $this->actingAs($this->admin)->deleteJson("/api/tasks/{$lastPost->id}");
        $response = $this->actingAs($this->admin)->getJson('/api/tasks');
        $response->assertStatus(200)->assertJsonCount(10, 'data.data');
    }
    // public function test_admin_can_see_all_tasks_without_trashed()
    // {
    //     Task::factory(5)->create([
    //         'user_id' => $this->admin->id,
    //     ]);
    //     $response = $this->actingAs($this->admin)->getJson('/api/tasks');
    //     $response->assertStatus(201)->assertJsonCount(5, 'data.data');
    //     // $response->assertStatus(201);

    //     $response = $this->actingAs($this->user)->getJson('/api/tasks');
    //     $response->assertStatus(201)->assertJsonCount(5, 'data.data');

    //     $response->assertJsonStructure([
    //         'data' => [
    //             'data' =>[
    //             ]
    //         ],
    //     ]);
    // }
}
