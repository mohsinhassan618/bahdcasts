<?php

namespace Tests\Unit;

use Bahdcasts\Lesson;
use Bahdcasts\User;
use function factory;
use Illuminate\Support\Collection;
use function in_array;
use Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function test_a_user_can_complete_a_lesson(){
        $this->withExceptionHandling();

        $this->RedisFlush();

        // User
        $user = factory(User::class)->create();


        $lesson = factory(Lesson::class)->create();// factory automatically creates the series

        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

        $user->CompleteLesson($lesson);

        $user->CompleteLesson($lesson2);

        $this->assertEquals(Redis::smembers("user:{$user->id}:series:{$lesson->series->id}"),[1,2]);

        $this->assertEquals($user->getNumberOfCompletedLessonsForASeries($lesson->series),2);

    }

    public function test_can_get_percentage_completed_for_series_for_a_user(){
        $this->withExceptionHandling();

        $this->RedisFlush();

        // User
        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create();// factory automatically creates the series

        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

        $lesson3 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

        $lesson4 =  factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);


        $user->CompleteLesson($lesson);

        $user->CompleteLesson($lesson2);

        $this->assertEquals(
            $user->PercentageCompletedForSeries($lesson->series),
            50
        );

    }

    public function test_can_know_if_a_user_has_started_a_series(){

        $this->RedisFlush();

        // User
        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create();// factory automatically creates the series

        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

        $lesson3 =  factory(Lesson::class)->create();

        $user->CompleteLesson($lesson2);

        $this->assertTrue($user->hasStartedSeries($lesson->series));
        $this->assertFalse($user->hasStartedSeries($lesson3->series));



    }

    public function test_can_get_completed_lessons_for_a_series(){

        //user , series

        $this->RedisFlush();

        // User
        $user = factory(User::class)->create();

        $lesson = factory(Lesson::class)->create();// factory automatically creates the series

        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

        $lesson3 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

        //completed some lessons in the series
        $user->CompleteLesson($lesson);
        $user->CompleteLesson($lesson2);

        //get completed lessons method
        $completedLessons = $user->getCompletedLessons($lesson->series);

        //assert that the lessons are in the return value

        $this->assertInstanceOf(Collection::class,$completedLessons);
        $this->assertInstanceOf(Lesson::class,$completedLessons->random());

        $lessonIds = $completedLessons->pluck('id')->all();

        $this->assertTrue(in_array($lesson->id,$lessonIds));
        $this->assertTrue(in_array($lesson2->id,$lessonIds));
        $this->assertFalse(in_array($lesson3->id,$lessonIds));

    }

    public function test_can_check_if_user_has_completed_lesson() {
        $this->RedisFlush();
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $lesson = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);
        //complete a lesson
        $user->completeLesson($lesson);
        //assert true,
        $this->assertTrue($user->hasCompletedLesson($lesson));
        $this->assertFalse($user->hasCompletedLesson($lesson2));
    }

    public function test_can_get_all_series_being_watched_by_user() {

        $this->withoutExceptionHandling();

        $this->RedisFlush();
        $user = factory(User::class)->create();
        $lesson1 = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson1->series->id ]);
        $lesson3 = factory(Lesson::class)->create();
        $lesson4 = factory(Lesson::class)->create([ 'series_id' => $lesson3->series->id ]);
        $lesson5 = factory(Lesson::class)->create();
        $lesson6 = factory(Lesson::class)->create([ 'series_id' => $lesson5->series->id ]);

        // complete lesson 1 , 2
        $user->completeLesson($lesson1);
        $user->completeLesson($lesson3);

        $startedSeries = $user->seriesBeingWatched();
        // collection of series
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $startedSeries);
        $this->assertInstanceOf(\Bahdcasts\Series::class, $startedSeries->random());
        $idsOfStartedSeries = $startedSeries->pluck('id')->all();

        $this->assertTrue(
            in_array($lesson1->series->id, $idsOfStartedSeries)
        );
        $this->assertTrue(
            in_array($lesson3->series->id, $idsOfStartedSeries)
        );
        $this->assertFalse(
            in_array($lesson6->series->id, $idsOfStartedSeries)
        );

    }

    public function test_can_get_number_of_completed_lessons_for_a_user(){

        $this->withoutExceptionHandling();

        $this->RedisFlush();
        $user = factory(User::class)->create();
        $lesson1 = factory(Lesson::class)->create();
        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson1->series->id ]);
        $lesson3 = factory(Lesson::class)->create();
        $lesson4 = factory(Lesson::class)->create([ 'series_id' => $lesson3->series->id ]);
        $lesson5 = factory(Lesson::class)->create();
        $lesson6 = factory(Lesson::class)->create([ 'series_id' => $lesson5->series->id ]);

        $user->completeLesson($lesson1);
        $user->completeLesson($lesson3);
        $user->completeLesson($lesson5);

        $this->assertEquals(3,$user->getTotalNumberOfCompletedLessons());

    }

    public function test_can_get_next_lesson_to_be_watched_by_user(){

        $this->withoutExceptionHandling();
        $this->RedisFlush();
        $user = factory(User::class)->create();
        $lesson1 = factory(Lesson::class)->create(['episode_number'   =>   100]);
        $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson1->series->id,'episode_number'   =>   200 ]);
        $lesson3 = factory(Lesson::class)->create([ 'series_id' => $lesson1->series->id,'episode_number'   =>   300 ]);
        $lesson4 = factory(Lesson::class)->create([ 'series_id' => $lesson1->series->id,'episode_number'   =>   400 ]);

        $user->completeLesson($lesson1);
        $user->completeLesson($lesson2);

        $nextLesson = $user->getNextLessonToWatch($lesson1->series);
        $this->assertEquals($lesson3->id,$nextLesson->id);

        $user->completeLesson($lesson3);

        $nextLesson = $user->getNextLessonToWatch($lesson1->series);
        $this->assertEquals($lesson4->id,$nextLesson->id);
    }

}
