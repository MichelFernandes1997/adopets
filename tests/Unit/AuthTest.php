<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class AuthTest extends TestCase
{
    public function test_it_shouldnt_be_Authenticated_with_invalid_email()
    {
        //$this->assertTrue(true);
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'im-the-invalid-@-my-little-chield',
            'password' => $user->password,
        ]);

        $response->assertStatus(422);
    }

    public function test_it_shouldnt_be_Authenticated_with_invalid_pass()
    {
        //$this->assertTrue(true);
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '1234567',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_shouldnt_be_Authenticated_with_wrong_email()
    {
        //$this->assertTrue(true);
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'im-the-wrong-email-my-little-chield@gmail.com',
            'password' => $user->password,
        ]);

        $response->assertStatus(401);
    }

    public function test_it_shouldnt_be_Authenticated_with_wrong_pass()
    {
        //$this->assertTrue(true);
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'im-the-wrong-pass-my-little-chield',
        ]);

        $response->assertStatus(401);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_should_be_Authenticated()
    {
        //$this->assertTrue(true);
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);

        $token = json_decode($response->getContent());

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

        $response->assertExactJson([
            'access_token' => $token->access_token,
            'token_type' => $token->token_type,
            'expires_in' => $token->expires_in,
        ]);
    }

    public function test_it_shouldnt_be_unauthenticate_token_is_invalid()
    {
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $token = JWTAuth::fromUser($user).'khsabdviy36';

        $response = $this->postJson('/api/logout', [],['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(401);

        $response->assertExactJson([
            'status' => 'Token is Invalid',
        ]);
    }

    public function test_it_shouldnt_be_unauthenticate_token_not_found()
    {
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/logout', []);

        $response->assertStatus(401);

        $response->assertExactJson([
            'status' => 'Authorization Token not found',
        ]);
    }

    public function test_it_should_be_unauthenticate()
    {
        $password = 'its-a-test-Rick';

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->postJson('/api/logout', [],['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);

        $response->assertExactJson([
            'logout' => true,
        ]);
    }
}
