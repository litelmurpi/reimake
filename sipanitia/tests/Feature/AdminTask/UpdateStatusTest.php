<?php

namespace Tests\Feature\AdminTask;

use App\Models\User;
use App\Models\Divisi;
use App\Models\AdminTask;
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

        // Setup Roles
        $bph = Role::create(['name' => 'bph']);
        $updateTask = Permission::create(['name' => 'admin_task.update']);
        $bph->givePermissionTo([$updateTask]);
    }

    public function test_anggota_can_update_status_only()
    {
        $user = User::factory()->create();
        $user->assignRole('bph');
        
        $divisi = Divisi::factory()->create(['nama_divisi' => 'BPH']);
        $user->divisis()->attach($divisi->id, ['is_koordinator' => false]);

        $task = AdminTask::factory()->create(['divisi_id' => $divisi->id, 'status' => 'Belum Mulai']);
        
        $this->assertTrue($user->can('update', $task));
    }

    public function test_status_change_updates_last_modified()
    {
        $user = User::factory()->create();
        $user->assignRole('bph');
        
        $divisi = Divisi::factory()->create(['nama_divisi' => 'BPH']);
        $user->divisis()->attach($divisi->id, ['is_koordinator' => true]);

        $task = AdminTask::factory()->create(['divisi_id' => $divisi->id, 'status' => 'Belum Mulai']);
        
        $this->actingAs($user);
        $task->update(['status' => 'Proses']);

        $this->assertEquals($user->id, $task->fresh()->last_modified_by);
    }
}
