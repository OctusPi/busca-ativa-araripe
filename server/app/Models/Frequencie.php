<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frequencie extends BaseModel
{
    use HasFactory;

    protected $table = 'frequencies';

    protected $fillable = [
        'registration',
        'subject',
        'teacher',
        'date_miss'
    ];
}
