<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">

    {{--<title>{{ config('app.name', 'Laravel') }}</title>--}}
    <title>The SaaS - Header Image</title>

    <!-- Styles -->
    <link href="{{ asset('css/core.min.css') }}"     rel="stylesheet">
    <link href="{{ asset('css/thesaas.min.css') }}"  rel="stylesheet">
    <link href="{{ asset('css/style.css') }}"        rel="stylesheet">
    {{--<link href="{{ asset('css/app.css') }}"          rel="stylesheet">--}}

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('header-script')

</head>
<body>
    <div id="app">


        <!-- Topbar -->
        <nav class="topbar topbar-inverse topbar-expand-md topbar-sticky">
            <div class="container">

                <div class="topbar-left">
                    <button class="topbar-toggler">&#9776;</button>
                    <a class="topbar-brand" href="">
                        <img class="logo-default" src="{{ asset('img/logo.png') }}" alt="logo">
                        <img class="logo-inverse" src="{{ asset('img/logo-light.png') }}" alt="logo">
                    </a>
                </div>


                <div class="topbar-right">
                    <ul class="topbar-nav nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="">Home</a>
                        </li>


                        @if( auth()->check())

                            @admin
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('series.index') }}">All Series </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('series.create') }}">Create Series </a>
                            </li>
                            @endadmin

                            <li class="nav-item">
                                <a class="nav-link" href=""  > Hey {{ auth()->user()->name }}</a>
                            </li>

                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#LoginModel" >Login</a>
                            </li>
                        @endif

                    </ul>
                </div>

            </div>
        </nav>
        <!-- END Topbar -->


        <!-- Header -->
        @yield('header')
        <!-- END Header -->




        <!-- Main container -->
        <main class="main-content">
            @yield('content')
        </main>
        <!-- END Main container -->


        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <div class="row gap-y align-items-center">
                    <div class="col-12 col-lg-6">
                        <ul class="nav nav-primary nav-hero">
                            <li class="nav-item">
                                <a class="nav-link" href="">Home</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->

        <vue-noty></vue-noty>

        @if( !auth()->check())
        <vue-login></vue-login>
        @endif

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/core.min.js') }}"></script>
    <script src="{{ asset('js/thesaas.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    @yield('footer-scripts')
</body>
</html>
