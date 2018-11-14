<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Bahdcasts\User;
use function factory;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    public function test_correct_response_after_user_log_in()
    {

        $user = factory(\Bahdcasts\User::class)->create();

        $this->postJson('/login',[
            'email' => $user->email,
            'password' => 'secret',
        ])->assertStatus(200)
            ->assertJson(
                [ 'status' => 'ok' ]
            )->assertSessionHas(['success' => 'Successfully logged in']);

    }

    public function test_a_user_receives_correct_message_when_passing_in_wrong_credentails()
    {

        $user = factory(\Bahdcasts\User::class)->create();

        $this->postJson('/login',[
            'email' => $user->email,
            'password' => "wrong-password",
        ])->assertStatus(422)
            ->assertJson(
                ['message' => "These credentials do not match our records."]
            );
    }
}
