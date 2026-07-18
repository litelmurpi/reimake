<?php

namespace Tests\Feature\AdminTask;

use App\Models\User;
use App\Models\Divisi;
use App\Models\AdminTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CreateAdminTaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $bph = Role::create(['name' => 'bph']);
        
        $createTask = Permission::create(['name' => 'admin_task.create']);
        $readTask = Permission::create(['name' => 'admin_task.read']);

        $bph->givePermissionTo([$createTask, $readTask]);
    }

    public function test_koordinator_can_create_task_in_own_divisi()
    {
        $user = User::factory()->create();
        // Give generic role to koordinator instead of BPH, or just give permission
        $divisi = Divisi::factory()->create(['nama_divisi' => 'Acara']);
        $user->divisis()->attach($divisi->id, ['is_koordinator' => true]);

        $task = AdminTask::factory()->make(['divisi_id' => $divisi->id]);
        
        $this->assertTrue($user->can('create', $task));
    }

    public function test_koordinator_cannot_create_task_in_other_divisi()
    {
        $user = User::factory()->create();
        
        $ownDivisi = Divisi::factory()->create(['nama_divisi' => 'Acara']);
        $otherDivisi = Divisi::factory()->create(['nama_divisi' => 'Perkap']);
        
        $user->divisis()->attach($ownDivisi->id, ['is_koordinator' => true]);

        $task = AdminTask::factory()->make(['divisi_id' => $otherDivisi->id]);
        
        $this->assertFalse($user->can('create', $task));
    }

    public function test_anggota_cannot_create_task()
    {
        $user = User::factory()->create();
        
        $divisi = Divisi::factory()->create(['nama_divisi' => 'Acara']);
        // Not koordinator
        $user->divisis()->attach($divisi->id, ['is_koordinator' => false]);

        $task = AdminTask::factory()->make(['divisi_id' => $divisi->id]);
        
        $this->assertFalse($user->can('create', $task));
    }
}
