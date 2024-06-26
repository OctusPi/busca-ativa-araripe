<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;

    protected string $table = 'teachers';
    protected $fillable = [
        'organ',
        'name',
        'registration',
        'cpf',
        'phone',
        'qualification'
    ];

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class, 'id', 'organ');
    } 

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
    }
}
