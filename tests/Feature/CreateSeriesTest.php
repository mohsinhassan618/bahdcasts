<?php

namespace Tests\Feature;

use Bahdcasts\User;
use function config;
use function factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function str_slug;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSeriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_series()
    {

        $this->withoutExceptionHandling();

        $this->LoginAdmin();


        Storage::fake(config('filesystems.default'));
        $this->post('admin/series',[
            'title' => 'vuejs for the best',
            'description' => "the best vue casts ever",
            'image' => UploadedFile::fake()->image('image-series.png'),
        ])->assertRedirect()
        ->assertSessionHas('success','Series created successfully');

        Storage::disk(config('filesystems.default'))->assertExists(
            'public/series/' . str_slug('vuejs for the best') .  "." . 'png'
        );
        $this->assertDatabaseHas('series',[
            'slug'  => str_slug('vuejs for the best')
        ]);

    }

    public function test_a_series_must_be_created_with_title(){
        //$this->withoutExceptionHandling();
       // ->assertRedirect('/admin/series/create')

        $this->LoginAdmin();

        $this->post('admin/series',[

            'description' => "the best vue casts ever",
            'image' => UploadedFile::fake()->image('image-series.png'),
        ])->assertSessionHasErrors('title');
    }

    public function test_a_series_must_be_created_with_description(){

        $this->LoginAdmin();
        //$this->withoutExceptionHandling();
        // ->assertRedirect('/admin/series/create')

        $this->post('admin/series',[

            'title' => "the best vue casts ever",
            'image' => UploadedFile::fake()->image('image-series.png'),
        ])->assertSessionHasErrors('description');
    }

    public function test_a_series_must_be_created_with_image(){

        $this->LoginAdmin();
        //$this->withoutExceptionHandling();
        // ->assertRedirect('/admin/series/create')

        $this->post('admin/series',[

            'title' => "the best vue casts ever",
            'description' => "the best vue casts ever",
        ])->assertSessionHasErrors('image');
    }

    public function test_a_series_must_be_created_with_image_with_string(){
        $this->LoginAdmin();
        //$this->withoutExceptionHandling();
        // ->assertRedirect('/admin/series/create')

        $this->post('admin/series',[

            'title' => 'vuejs for the best',
            'description' => "the best vue casts ever",
            'image' => "STRING_INVALID_IMAGE",
        ])->assertSessionHasErrors('image');
    }

    public function test_only_administrators_can_create_series(){

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->post('admin/series',[
            'title' => 'vuejs for the best',
            'description' => "the best vue casts ever",
            'image' => UploadedFile::fake()->image('image-series.png'),
        ])->assertSessionHas('error', "You are not authorized to perform this action");

    }

}
