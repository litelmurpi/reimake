<?php

namespace Tests\Feature\EventTask;

use App\Models\User;
use App\Models\Event;
use App\Models\EventTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CreateEventTaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $createEvent = Permission::create(['name' => 'event_task.create']);
        $readEvent = Permission::create(['name' => 'event_task.read']);

        $acara = Role::create(['name' => 'sie_acara']);
        $acara->givePermissionTo([$createEvent, $readEvent]);
    }

    public function test_sie_acara_can_create_event_task()
    {
        $user = User::factory()->create();
        $user->assignRole('sie_acara');
        
        $event = Event::factory()->create();
        
        $task = EventTask::factory()->make(['event_id' => $event->id]);
        
        $this->assertTrue($user->can('create', $task));
    }

    public function test_bph_cannot_create_event_task()
    {
        $bph = Role::create(['name' => 'bph']);
        
        $user = User::factory()->create();
        $user->assignRole('bph');
        
        $event = Event::factory()->create();
        $task = EventTask::factory()->make(['event_id' => $event->id]);
        
        // Only Sie Acara should manage EventTasks
        $this->assertFalse($user->can('create', $task));
    }
}
