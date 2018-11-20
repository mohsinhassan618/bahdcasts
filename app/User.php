<?php

namespace Bahdcasts;

use Bahdcasts\Entities\Learning;
use function config;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use function in_array;
use Laravel\Cashier\Billable;
use Redis;

class User extends Authenticatable
{
    use Notifiable,Learning, Billable;

    protected $with = ['subscriptions'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','username', 'email', 'password','confirm_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


}

