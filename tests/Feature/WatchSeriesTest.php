<?php

namespace Tests\Feature;

use Bahdcasts\Lesson;
use Bahdcasts\User;
use function factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WatchSeriesTest extends TestCase
{
    use RefreshDatabase;


    public function test_a_user_can_complete_a_series(){

        $this->RedisFlush();

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $lesson1 = factory(Lesson::class)->create();

        $lesson2 = factory(Lesson::class)->create(['series_id' => $lesson1->series->id ]);

        $response = $this->post("/series/complete-lesson/{$lesson1->id}",[]);

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 'ok'
        ]);

        $this->assertTrue(
            $user->hasCompletedLesson($lesson1)
        );

        $this->assertFalse(
            $user->hasCompletedLesson($lesson2)
        );
    }


    public function test_can_check_if_user_has_completed_lesson(){

        $this->RedisFlush();

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $lesson1 = factory(Lesson::class)->create();

        $lesson2 = factory(Lesson::class)->create(['series_id' => $lesson1->series->id ]);

        $user->CompleteLesson($lesson1);

        $this->assertTrue($user->hasCompletedLesson($lesson1));

        $this->assertFalse($user->hasCompletedLesson($lesson2));

    }

}
