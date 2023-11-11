<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Travel $travel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travel = Travel::factory()->create();
    }

    public function test_public_user_cannot_access_update_travel(): void
    {
        $response = $this->putJson('/api/v1/admin/travels/'.$this->travel->id);

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_update_travel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));

        $response = $this->actingAs($user)->putJson('/api/v1/admin/travels/'.$this->travel->id);

        $response->assertStatus(403);
    }

    public function test_validation_update_travel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));

        $response = $this->actingAs($user)->putJson('/api/v1/admin/travels/'.$this->travel->id);

        $response->assertStatus(422);
    }

    public function test__admin_user_can_access_update_travel(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));

        $response = $this->actingAs($user)->putJson('/api/v1/admin/travels/'.$this->travel->id, [
            'is_public' => true,
            'name' => 'travel10',
            'description' => 'test',
            'number_of_days' => 20,
        ]);

        $response->assertStatus(200);
    }
}
