<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp(): void{
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }


    public function test_register_user(){
        $response = $this->post(route(name: 'auth.register'), [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'confirm_password' => 'password',
        ]);


        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Test User');

        // dump($response->getContent());
    }

    public function test_login_user(){
        $response = $this->post(route('auth.login'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'confirm_password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.token', fn($token): bool => isset($token));

        dump($response->getContent());
    }

}
