<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequencie extends Model
{
    use HasFactory;

    public string $table = 'frequencies';

    protected $fillable = [
        'registration',
        'subject',
        'teacher',
        'date_miss'
    ];
}
