<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('make:bigboss', function () {
    $this->info('Enter data to create a super user');
    
    $data = [];
    $data['name']     = $this->ask('Type username:');
    $data['email']    = $this->ask('Type email:');
    $data['password'] = $this->ask('Type password:');
    $data['username'] = $data['email'];
    $data['password'] = Hash::make($data['password']);
    $data['profile']  = User::P_ADMIN;
    $data['modules']  = User::list_modules();
    $data['status']   = true;
    User::factory()->create($data);
    $this->info('Sussefully!!!');
    
    
})->purpose('Create a default super user to access system');
