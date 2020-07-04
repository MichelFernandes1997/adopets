<?php

namespace Tests\Unit;

use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_should_create_user()
    {
        // $this->assertTrue(true);
        $data = [
            'nome' => 'Michel Fernandes da Silva Alves',
            'email' => 'michelfsa@live.com',
            'password' => '1m4d3v3l0p3r1nt3st'
        ];

        $response = $this->postJson(route('users.store'), $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'user' =>
            [
                'id',
                'nome',
                'email'
            ]
        ]);
    }
}
