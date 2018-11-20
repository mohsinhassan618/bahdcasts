<?php

namespace Bahdcasts\Entities;


use function array_push;
use Bahdcasts\Lesson;
use Bahdcasts\Series;
use function collect;
use function count;
use function explode;
use function in_array;
use Redis;

trait Learning {

    public function isConfirmed()
    {
        return $this->confirm_token == Null;
    }

    public function confirm()
    {
        $this->confirm_token = null;
        $this->save();
    }
    public function isAdmin(){
        return in_array($this->email ,config('bahdcasts.administrators'));
    }

    public function CompleteLesson($lesson){
        // dd("user:{$this->id}:series:{$lesson->series_id}");
        Redis::sadd("user:{$this->id}:series:{$lesson->series_id}",$lesson->id);
    }

    public function PercentageCompletedForSeries($series){
        $series->refresh();
        $numberOfLesonsInSeries   = $series->lessons->count();
        $numberOfCompletedLessons = $this->getNumberOfCompletedLessonsForASeries($series);
        return ($numberOfCompletedLessons/$numberOfLesonsInSeries) * 100 ;
    }

    public function getNumberOfCompletedLessonsForASeries($series){
        return count($this->getCompletedLessonsForASeries($series));
    }

    public function getCompletedLessonsForASeries($series){
        return Redis::smembers("user:{$this->id}:series:{$series->id}");
    }

    public function hasStartedSeries($series){
        return $this->getNumberOfCompletedLessonsForASeries($series) > 0 ;
    }

    public function getCompletedLessons($series){
        $completedLessons = $this->getCompletedLessonsForASeries($series);

        return collect($completedLessons)->map(function ($lessonId){
            return Lesson::find($lessonId);
        });
    }

    public function hasCompletedLesson($lesson){
        return in_array($lesson->id,$this->getCompletedLessonsForASeries($lesson->series));
    }

    public function seriesBeingWatchedIds(){

        $keys = Redis::Keys("user:{$this->id}:series:*");
        $seriesIds = [];
        foreach ($keys as $key){
            $seriesId = explode(':',$key)[3];
            array_push($seriesIds,$seriesId);
        }
        return $seriesIds;
    }

    public function seriesBeingWatched(){
        return collect($this->seriesBeingWatchedIds())->map(function($id){
            return Series::find($id);
        })->filter();
    }

    public function getTotalNumberOfCompletedLessons(){
      $keys =  Redis::Keys("user:{$this->id}:series:*");
      $result = 0;

      foreach ($keys as $key):
          $result = $result + count(Redis::smembers($key));
      endforeach;

      return $result;

    }

    public function getRouteKeyName(){
        return 'username';
    }

    public function getNextLessonToWatch(Series $series){


        $lessonIds = $this->getCompletedLessonsForASeries($series);

        $lessonId  = end($lessonIds);

        return Lesson::find($lessonId)->getNextLesson();


    }

}