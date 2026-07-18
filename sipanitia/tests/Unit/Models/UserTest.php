<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_belongs_to_many_divisi(): void
    {
        $user = User::factory()->create();
        $divisi = Divisi::factory()->create(['nama_divisi' => 'Sie Acara']);

        $user->divisis()->attach($divisi->id, ['is_koordinator' => true]);

        $this->assertTrue($user->belongsToDivisi($divisi));
        $this->assertTrue($user->isKoordinatorOf($divisi));
    }

    public function test_user_has_role(): void
    {
        Role::create(['name' => 'bph']);
        $user = User::factory()->create();
        $user->assignRole('bph');

        $this->assertTrue($user->hasRole('bph'));
    }
}
