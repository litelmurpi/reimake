<?php

namespace Tests\Feature\EventTask;

use App\Models\User;
use App\Models\Event;
use App\Models\EventTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UpdateStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $updateEvent = Permission::create(['name' => 'event_task.update']);
        $acara = Role::create(['name' => 'sie_acara']);
        $acara->givePermissionTo([$updateEvent]);
    }

    public function test_sie_acara_can_update_event_task()
    {
        $sieAcara = \App\Models\Divisi::factory()->create(['nama_divisi' => 'Sie Acara']);
        
        $user = User::factory()->create();
        $user->assignRole('sie_acara');
        $user->divisis()->attach($sieAcara->id);
        
        $event = Event::factory()->create();
        $task = EventTask::factory()->create(['event_id' => $event->id, 'status' => 'Draft']);
        
        $this->assertTrue($user->can('update', $task));
    }

    public function test_update_status_updates_last_modified_by()
    {
        $user = User::factory()->create();
        $user->assignRole('sie_acara');
        
        $event = Event::factory()->create();
        $task = EventTask::factory()->create(['event_id' => $event->id, 'status' => 'Draft']);
        
        $this->actingAs($user);
        $task->update(['status' => 'Approved']);

        $this->assertEquals($user->id, $task->fresh()->last_modified_by);
    }
}
