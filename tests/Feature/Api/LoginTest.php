<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'ali@admin.com'
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    }

    public function test_login_fail_with_email_not_exists(): void
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'test@email.com',
            'password' => 'password'
        ]);

        $response->assertStatus(302);
    }

    public function test_login_fail_with_email_exists(): void
    {
        $user = User::factory()->create([
            'email' => 'ali@admin.com'
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'passworddddd'
        ]);

        $response->assertStatus(422);
    }
}
