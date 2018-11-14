<?php

namespace Tests\Unit;

use function asset;
use Bahdcasts\Lesson;
use function factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeriesTest extends TestCase
{

    use RefreshDatabase;


    public function test_series_can_get_image_path(){

        $series = factory(\Bahdcasts\Series::class)->create([
            'image_url' => 'series/series-slug.png'
        ]);

        $this->assertEquals(asset('storage/series/series-slug.png'),$series->image_url);

    }

    public function test_can_get_ordered_lessons_for_a_series(){


        $lesson  = factory(Lesson::class)->create(['episode_number' => 200 ]);
        $lesson1 = factory(Lesson::class)->create(['episode_number' => 100 , 'series_id' => $lesson->series->id]);
        $lesson2 = factory(Lesson::class)->create(['episode_number' => 300 , 'series_id' => $lesson->series->id]);


        $lessons = $lesson->series->getOrderedLessons();


        $this->assertInstanceOf(Lesson::class,$lessons->random());
        $this->assertEquals($lessons->first()->id,$lesson1->id);
        $this->assertEquals($lessons->last()->id,$lesson2->id);


    }

}

