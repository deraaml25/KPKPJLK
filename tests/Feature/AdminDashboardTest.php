<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('renders the admin dashboard for super admins', function () {
    $user = User::create([
        'name' => 'Administrator',
        'username' => 'admin',
        'password' => Hash::make('password'),
        'role' => 'super_admin',
    ]);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertStatus(200);
    $response->assertViewIs('admin.dashboard');
    $response->assertSee('Dashboard');
});
