<?php

namespace Bahdcasts;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    //
    protected $guarded = [];
    protected $with = ['lessons']; // Always load this model with series

    public function lessons(){
        return $this->hasMany(Lesson::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageUrlAttribute($url){
        return asset('storage/' . $url);
    }

    public function getOrderedLessons(){
        return $this->lessons()->orderBy('episode_number','asc')->get();
    }
}
