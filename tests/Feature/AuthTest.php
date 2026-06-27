<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Medora');
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('login_post', [
            'email' => 'unknown@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::create([
            'name' => 'Test',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_role' => 1,
            'status' => 1,
            'is_deleted' => 0,
        ]);

        $response = $this->post('login_post', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login()
    {
        User::create([
            'name' => 'Test',
            'last_name' => 'Inactive',
            'email' => 'inactive@example.com',
            'password' => bcrypt('password123'),
            'is_role' => 2,
            'status' => 0, // Inactive
            'is_deleted' => 0,
        ]);

        $response = $this->post('login_post', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'This account is inactive. Please contact admin.');
    }

    public function test_deleted_user_cannot_login()
    {
        User::create([
            'name' => 'Test',
            'last_name' => 'Deleted',
            'email' => 'deleted@example.com',
            'password' => bcrypt('password123'),
            'is_role' => 2,
            'status' => 1,
            'is_deleted' => 1, // Deleted
        ]);

        $response = $this->post('login_post', [
            'email' => 'deleted@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'This account has been deleted.');
    }

    public function test_staff_user_cannot_access_admin_routes()
    {
        $staff = User::create([
            'name' => 'Test',
            'last_name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => bcrypt('password123'),
            'is_role' => 2, // Staff
            'status' => 1,
            'is_deleted' => 0,
        ]);

        // Login as staff
        $this->actingAs($staff);

        // Try to access admin/users list
        $response = $this->get('admin/users');

        $response->assertRedirect('admin/dashboard');
        $response->assertSessionHas('error', 'Access Denied: Admin privileges required.');
    }
}
