<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can view task management page with own tasks only', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Task::create([
        'user_id' => $user->id,
        'title' => 'Review database lesson',
        'category' => 'study',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    Task::create([
        'user_id' => $otherUser->id,
        'title' => 'Other user private task',
        'category' => 'work',
        'priority' => 'medium',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->get(route('tasks.index'));

    $response->assertOk();
    $response->assertSee('Task Management');
    $response->assertSee('Review database lesson');
    $response->assertDontSee('Other user private task');
    $response->assertSee('Study (1)');
});

test('authenticated user can create a categorized task', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('tasks.store'), [
        'title' => 'Prepare study notes',
        'description' => 'Read chapter 3 and summarize it.',
        'category' => 'study',
        'priority' => 'high',
        'due_date' => '2026-03-30',
    ]);

    $response->assertRedirect(route('tasks.index', ['category' => 'study']));
    $response->assertSessionHas('task_status', 'Task created successfully.');

    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title' => 'Prepare study notes',
        'category' => 'study',
        'priority' => 'high',
        'status' => 'pending',
    ]);
});

test('authenticated user can mark a task completed', function () {
    $user = User::factory()->create();
    $task = Task::create([
        'user_id' => $user->id,
        'title' => 'Go to the gym',
        'category' => 'health',
        'priority' => 'medium',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->patch(route('tasks.status', $task->id), [
        'status' => 'completed',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'completed',
    ]);
});

test('home page shows task progress stats and category icons', function () {
    $user = User::factory()->create();

    Task::create([
        'user_id' => $user->id,
        'title' => 'Review math lesson',
        'category' => 'study',
        'priority' => 'high',
        'status' => 'completed',
    ]);

    Task::create([
        'user_id' => $user->id,
        'title' => 'Walk 20 minutes',
        'category' => 'health',
        'priority' => 'medium',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
    $response->assertSee('Task Progress');
    $response->assertSee('Completion Rate');
    $response->assertSee('50%');
    $response->assertSee('📚', false);
    $response->assertSee('💪', false);
    $response->assertSee('Study');
    $response->assertSee('Health');
});