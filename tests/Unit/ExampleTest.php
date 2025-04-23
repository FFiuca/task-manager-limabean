<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    use DatabaseMigrations;
    // use RefreshDatabase;


    public function setUp(): void{
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    public function test_connect_db_testing(){
        $user = User::count();

        $this->assertTrue($user > 0);
    }
}
