<?php

namespace Bahdcasts\Http\Controllers;

use Bahdcasts\User;
use Illuminate\Http\Request;
use function redirect;
use function session;

class ConfirmEmailController extends Controller
{
    //

    public function index()
    {

        $token = request('token');
        $user = User::where("confirm_token" , $token)->get();



        if(isset($user[0])){
            $user[0]->confirm();
            session()->flash("success","Your email has been confirmed.");
            return redirect('/');
        } else {
            session()->flash("error","Confirmation token not recognised.");
            return redirect("/");
        }

    }


}

