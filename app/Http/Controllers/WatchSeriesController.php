<?php

namespace Bahdcasts\Http\Controllers;

use function auth;
use Bahdcasts\Lesson;
use Bahdcasts\Series;
use Illuminate\Http\Request;
use function redirect;
use function response;
use function view;

class WatchSeriesController extends Controller
{
    //
    public function index(Series $series){

        return redirect()->route('series.watch',
            [
                'series' => $series->slug,
                'lesson' => $series->lessons->first()->id
            ]);

    }

    public function showLesson(Series $series,Lesson $lesson) {

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
