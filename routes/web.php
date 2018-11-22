<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Bahdcasts\Lesson;
use Bahdcasts\Series;
use Bahdcasts\User;



Auth::routes();

Route::get('/', 'FrontendController@welcome');

Route::get('/email', function () {
    return new \Bahdcasts\Mail\ConfirmYourEmail();
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get("/logout" , function (){
    auth()->logout();
});

Route::get('register/confirm',"ConfirmEmailController@index")->name('confirm-email');

Route::get('/series/{series}','FrontendController@series')->name('series');

Route::get('/series/{series}/lesson/{lesson}','WatchSeriesController@showLesson')->name('series.watch');

Route::middleware('profile')->group(function(){
    Route::get('/profile/{user}','ProfilesController@index');
});



Route::middleware('auth')->group(function (){
    Route::post('/series/complete-lesson/{lesson}','WatchSeriesController@completeLesson');
    Route::get('/watch-series/{series}','WatchSeriesController@index')->name('series.learning');
    Route::get('/subscribe','SubscriptionsController@showSubscriptionForm');
    Route::post('/subscribe','SubscriptionsController@subscribe');
    Route::post('/subscription/change','SubscriptionsController@change')->name('subscriptions.change');
    Route::post('/card/update','ProfilesController@updateCard');
});

//Route::get('{series_by_id}', function (\Bahdcasts\Series $series){
//    dd($series);
//});

Route::get('/test',function(){
    //$user = \Bahdcasts\User::where('username','edwin-daiz')->get();
//    $serires = Series::find(1);
//    dd($serires->lessons);

    // User
    $user = factory(User::class)->create();

    $lesson = factory(Lesson::class)->create();// factory automatically creates the series

    $lesson2 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

    $lesson3 = factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

    $lesson4 =  factory(Lesson::class)->create([ 'series_id' => $lesson->series->id ]);

    $series = $lesson->series;
    $series->refresh();

    dd($series->lessons);
//        $serires =  \Bahdcasts\Series::find($lesson->series->id);
//        dd($serires->lessons);
});

Route::get('/testredis',function (){


    // Key: value // string
    //Redis::set('friend','momo');
    // dd( Redis::get('friend')  );


    // Key: value // list
    Redis::lpush('frameworks','vuejs');
    Redis::lpush('frameworks','laravel');

    dd(Redis::lrange('frameworks',0,-1));

    // Key: value // set

    //Redis::sadd('frontend-framework',['angular','embar']);

    // dd(Redis::smembers('frontend-framework'));

});

