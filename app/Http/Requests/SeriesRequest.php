<?php

namespace Bahdcasts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeriesRequest extends FormRequest
{

    public function uploadSeriesImage(){

        $uploadedImage = $this->image;
        $this->filename = str_slug($this->title) . ".". $uploadedImage->getClientOriginalExtension();
        //Upload file
        $uploadedImage->storePubliclyAs('public/series',$this->filename);

        return $this;

    }
}
