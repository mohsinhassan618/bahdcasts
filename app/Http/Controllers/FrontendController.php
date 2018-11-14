<?php

namespace Bahdcasts\Http\Controllers;

use Bahdcasts\Series;
use Illuminate\Http\Request;
use function value;
use function view;

class FrontendController extends Controller
{
    //
    public function welcome()
    {
        return view('welcome')->withSeries(Series::all());
    }

    public function series(Series $series){

        return view('series')->withSeries($series);
    }

}
