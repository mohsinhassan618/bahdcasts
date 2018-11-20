<?php

namespace Tests\Feature;

use Bahdcasts\Lesson;
use Bahdcasts\User;
use function factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionsTest extends TestCase
{

    use RefreshDatabase;

    public function test_a_user_without_a_plan_cannot_watch_premium_lessons(){

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $lesson = factory(Lesson::class)->create([ 'premium' => 1 ]);

        $this->get("/series/{$lesson->series->slug}/lesson/{$lesson->id}")->assertRedirect('/subscribe');

//        $this->fakeSubscribe($user);
//
//        dd($this->subscribed('yearly'));

    }

    public function fakeSubscribe ($user){

        //subscriptions

        return $user->subscriptions()->create([
            'name'        => 'yearly-plan',
            'stripe_id'   => 'FAKE_STRIPE_ID',
            'stripe_plan' => 'yearly-plan',
            'quantity'    => 1
        ]);
    }

    public function test_a_user_on_any_plan_can_watch_all_lessons(){

        $this->withExceptionHandling();

        $user = factory(User::class)->create();

        $this->actingAs($user);
        $fakeSub = $this->fakeSubscribe($user);
       // dd($fakeSub);

        $lesson = factory(Lesson::class)->create([ 'premium' => 1 ]);
        $lesson2 = factory(Lesson::class)->create([ 'premium' => 0 ]);
       // dd(auth()->user()->subscribed('yearly-plan'));

        $this->get("/series/{$lesson->series->slug}/lesson/{$lesson->id}")->assertViewIs('watch');
        $this->get("/series/{$lesson2->series->slug}/lesson/{$lesson2->id}")->assertViewIs('watch');


    }
}
