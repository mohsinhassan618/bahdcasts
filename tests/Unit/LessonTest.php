<?php

namespace Tests\Unit;

use Bahdcasts\Lesson;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonTest extends TestCase
{

    use RefreshDatabase;


    public function test_can_get_next_previous_lessons_form_a_lesson (){
        $lesson  = factory(Lesson::class)->create(['episode_number' => 200 ]);
        $lesson2 = factory(Lesson::class)->create(['episode_number' => 100 , 'series_id' => $lesson->series->id]);
        $lesson3 = factory(Lesson::class)->create(['episode_number' => 300 , 'series_id' => $lesson->series->id]);


        $this->assertEquals($lesson->getNextLesson()->id,$lesson3->id);
        $this->assertEquals($lesson3->getPrevLesson()->id,$lesson->id);
        $this->assertEquals($lesson2->getNextLesson()->id,$lesson->id);

        $this->assertEquals($lesson3->getNextLesson()->id,$lesson3->id);
        $this->assertEquals($lesson2->getPrevLesson()->id,$lesson2->id);

        //$this->assertNull($lesson2->getPrevLesson());
        //$this->assertNull($lesson3->getNextLesson());


    }
}
