<?php

namespace Tests;

use Illuminate\Support\Facades\Config;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Bahdcasts\User;
use Illuminate\Support\Facades\Redis;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function LoginAdmin(){
        $user = factory(User::class)->create();
        Config::push('bahdcasts.administrators' , $user->email );
        $this->actingAs($user);
    }

    public function RedisFlush(){
        Redis::flushAll();
    }
}
