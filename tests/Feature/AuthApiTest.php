<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test User Can Register .
     *
     * @return void
     */
    public function test_user_can_register_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'name' => 'Test User',
                'email' => 'testuser@example.com',
            ],
        ]);
    }

    public function test_registration_validation_errors()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'different-password',
        ]);
        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_cannot_register_with_exists_email()
    {
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(400);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login_successfully()
    {

        $user = User::create([
            'name' => 'Abdelkadir',
            'email' => 'abdelkadir@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'abdelkadir@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token',
            'data' => [
                'id',
                'name',
                'email',
                'role',
            ],
        ]);
    }
    public function test_user_login_error_with_incorrect_credentials(){
        $user = User::create([
            'name' => 'Abdelkadir',
            'email' => 'abdelkadir12@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'abdelkadir12@example.com',
            'password' => 'password123',
        ]);   
        $response->assertStatus(401);

        // Assert that the user is not authenticated
        $this->assertGuest();
    }
}
