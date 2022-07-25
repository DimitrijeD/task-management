<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register()
    {
        $data = [
            'name' => "Usernme",
            'email' => "email@email",
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post(route("register"), $data);

        $response->assertRedirect(route('home'));


        $this->assertDatabaseHas((new User)->getTable(), [
            'email' => $data['email']
        ]);
    }

    public function test_can_login()
    {
        $user = User::factory()->create();
        
        $data = [
            'email' => $user->email,
            'password' => $user->password,
        ];

        $response = $this->post(route("login"), $data);

        $response->assertRedirect(route('home'));
    }
}
