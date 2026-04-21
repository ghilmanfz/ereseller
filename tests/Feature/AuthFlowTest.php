<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_normalizes_whatsapp_and_redirects(): void
    {
        $response = $this->post('/register', [
            'name' => 'User Format',
            'whatsapp' => '+62 822-3333-4444',
            'email' => 'user.format@example.com',
            'address' => 'Parungpanjang',
            'password' => 'password123',
            'terms' => 'on',
        ]);

        $response->assertRedirect('/katalog');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'User Format',
            'whatsapp' => '082233334444',
            'email' => 'user.format@example.com',
            'role' => 'customer',
            'status' => 'active',
        ]);
    }

    public function test_login_accepts_formatted_whatsapp_input(): void
    {
        User::query()->create([
            'name' => 'Customer Demo',
            'whatsapp' => '082222222222',
            'address' => 'Parungpanjang',
            'email' => 'customer.demo@sr12.local',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'whatsapp' => '+62 822-2222-2222',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/katalog');
        $this->assertAuthenticated();
    }

    public function test_customer_can_update_profile_data(): void
    {
        $user = User::query()->create([
            'name' => 'Customer Lama',
            'whatsapp' => '082111111111',
            'address' => 'Alamat Lama',
            'email' => 'lama@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->patch('/profil', [
            'name' => 'Customer Baru',
            'whatsapp' => '+62 821-5555-6666',
            'email' => 'baru@example.com',
            'address' => 'Alamat Baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Customer Baru',
            'whatsapp' => '082155556666',
            'address' => 'Alamat Baru',
            'email' => 'baru@example.com',
        ]);
    }

    public function test_customer_can_change_password_from_profile(): void
    {
        $user = User::query()->create([
            'name' => 'Customer Demo',
            'whatsapp' => '082333333333',
            'address' => 'Parungpanjang',
            'email' => '082333333333@sr12.local',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->patch('/profil/password', [
            'current_password' => 'password123',
            'password' => 'password456',
            'password_confirmation' => 'password456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('password456', $user->password));
    }

    public function test_forgot_password_can_reset_by_whatsapp(): void
    {
        $user = User::query()->create([
            'name' => 'Customer Demo',
            'whatsapp' => '082444444444',
            'address' => 'Parungpanjang',
            'email' => 'customer.reset@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        $response = $this->post('/lupa-password', [
            'email' => 'customer.reset@example.com',
            'password' => 'password999',
            'password_confirmation' => 'password999',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        $login = $this->post('/login', [
            'whatsapp' => '082444444444',
            'password' => 'password999',
        ]);

        $login->assertRedirect('/katalog');
    }
}
