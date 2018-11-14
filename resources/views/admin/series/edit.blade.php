@extends('layouts.app')

@section('header')
    <!-- Header -->
    <header class="header header-inverse" style="background-color: #c2b2cd;">
        <div class="container text-center">

            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">

                    <h1> Edit: {{  $series->title }}</h1>
                    <p class="fs-20 opacity-70">You can find several product design by our professional team in this section.</p>

                </div>
            </div>

        </div>
    </header>
    <!-- END Header -->
@endsection

@section("content")



    <div class="section">
        <div class="container">

            <div class="row gap-y">
                <div class="col-12 ">

                    <form action="{{ route('series.update',$series->slug) }}" method="POST" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group">
                            <input class="form-control form-control-lg" value="{{  $series->title }}" type="text" name="title" placeholder="Title">
                        </div>

                        <div class="form-group">
                            <input class="form-control form-control-lg" type="file" name="image" >
                        </div>


                        <div class="form-group">
                            <textarea class="form-control form-control-lg"  name="description" rows="4" placeholder="Description"  >{{ $series->description }}</textarea>
                        </div>


                        <button class="btn btn-lg btn-primary btn-block" type="submit">Create Series</button>
                    </form>

                </div>



            </div>

        </div>
    </div>

@endsection