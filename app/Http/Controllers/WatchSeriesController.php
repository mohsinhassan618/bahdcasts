<?php

namespace Bahdcasts\Http\Controllers;

use function auth;
use Bahdcasts\Lesson;
use Bahdcasts\Series;
use function redirect;
use function response;
use function view;

class WatchSeriesController extends Controller
{
    //
    public function index(Series $series){

        $user = auth()->user();

        if($user->hasStartedSeries($series)){
            return redirect()->route('series.watch',
                [
                    'series' => $series->slug,
                    'lesson' => $user->getNextLessonToWatch($series)
                ]);
        }

        return redirect()->route('series.watch',
            [
                'series' => $series->slug,
                'lesson' => $series->lessons->first()->id
            ]);
    }

    public function showLesson(Series $series,Lesson $lesson) {

        $user_subscription = (auth()->user()->subscribed('yearly-plan') || auth()->user()->subscribed('monthly-plan') ) ? true : false;


        if($lesson->premium && !$user_subscription ){
            return redirect('/subscribe');
        }

        return view('watch',[
            'series' => $series,
            'lesson' => $lesson
        ]);

    }

    public function completeLesson(Lesson $lesson){


        auth()->user()->CompleteLesson($lesson);

        return response()->json([
            'status' => 'ok'
        ]);
    }
}