<?php

namespace Bahdcasts\Http\Controllers;

use Bahdcasts\User;
use function view;

class ProfilesController extends Controller
{
    //

    public function index(User $user){

        return view('profile')
            ->withUser($user)
            ->withSeries($user->seriesBeingWatched());
    }
}
