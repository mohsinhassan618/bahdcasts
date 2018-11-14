<?php

namespace Tests\Feature;


use Bahdcasts\User;
use Illuminate\Support\Facades\Mail;
use function str_slug;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Bahdcasts\Mail\ConfirmYourEmail;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_has_a_default_username_after_registration()
    {
        $this->post('/register',[
            'name'  => 'mohsin hassan',
            'email' => 'mohsin@hassan.com',
            'password' => 'secret'
            ])->assertRedirect();

        $this->assertDatabaseHas('users',[
            'username' => str_slug('mohsin hassan')
        ]);
    }


    public function test_an_email_is_sent_to_newly_registered_users()
    {

        $this->withoutExceptionHandling();

        Mail::Fake();

        // register new user
        $this->post('/register',[
            'name'  => 'mohsin hassan',
            'email' => 'mohsin@hassan.com',
            'password' => 'secret'
        ])->assertRedirect();

        //assert mai was sent

        Mail::assertSent(ConfirmYourEmail::class);

    }

    public function test_a_user_has_a_token_after_registration()
    {
        $this->withoutExceptionHandling();

        Mail::Fake();

        // register new user
        $this->post('/register',[
            'name'  => 'mohsin hassan',
            'email' => 'mohsin@hassan.com',
            'password' => 'secret'
        ])->assertRedirect();

        $user = User::find(1);

        $this->assertNotNull($user->confirm_token);

        $this->assertFalse($user->isConfirmed());



    }

}
