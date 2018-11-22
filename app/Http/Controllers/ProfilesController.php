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

    public function updateCard(){
        $this->validate(request(),[
            'stripeToken' => 'required'
        ]);

        $stripeToken = request('stripeToken');
        $user = auth()->user();
        $user->updateCard($stripeToken);

        return response()->json(['status' => 'ok' ]);
    }
}
