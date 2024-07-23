<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('make:bigboss', function () {
    $way = $this->choice('Create a Super User: Enter data manually?', ['no', 'yes'], 'yes');

    if($way == 'yes'){
        $data = [];

        $data['name']     = $this->ask('Type username:');
        $data['email']    = $this->ask('Type email:');
        $data['password'] = $this->ask('Type password:');
        $data['username'] = $data['email'];
        $data['password'] = Hash::make($data['password']);

        (new DatabaseSeeder())->run($data);
        $this->info('Sussefully!!!');
    }else{
        $this->call('db:seed');
    }
    
})->purpose('Create a default super user to access system');
