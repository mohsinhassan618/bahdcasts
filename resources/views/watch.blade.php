@extends('layouts.app')

@section('header')
    <header class="header header-inverse" style="background-color: #9ac29d">
        <div class="container text-center">
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">
                    <h1>{{ $lesson->title }}</h1>
                    <p class="fs-20 opacity-70">{{  $series->title }}</p>
                </div>
            </div>
        </div>
    </header>
@endsection
@php
$nextLesson = $lesson->getNextLesson();
$prevLesson = $lesson->getPrevLesson();
@endphp
@section('content')
    <div class="section bg-grey">
        <div class="container">

            <div class="row gap-y text-center">
                <div class="col-12">
                    <vue-player default_lesson="{{ $lesson  }}"
                                @if($nextLesson)
                                next_lesson_url="{{ route('series.watch', ['series' => $series->slug , 'lesson' => $nextLesson ]) }}"
                                @endif
                    ></vue-player>

                    @if($nextLesson->id != $lesson->id)
                        <a href="{{ route('series.watch',[ 'series' => $series->slug , 'lesson' => $nextLesson ]) }}" class="btn btn-info">Next Lesson</a>
                    @endif

                    @if($prevLesson->id != $lesson->id)
                    <a href="{{ route('series.watch',[ 'series' => $series->slug , 'lesson' => $prevLesson ])  }}" class="btn btn-info">Previous Lesson</a>
                    @endif

                </div>

                <div class="col-12">
                    <ul class="list-group">
                        @foreach($series->getOrderedLessons() as $l)
                            <li class="list-group-item
                            @if($l->id == $lesson->id)
                                    active
                            @endif
                             ">
                                @if( auth::check() && auth()->user()->CompleteLesson($l))
                                    <b><small>COMPLETED</small></b>
                                @endif
                                <a href="{{ route('series.watch', ['series' => $series->slug , 'lesson' => $l->id]) }}" >{{ $l->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection