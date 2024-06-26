<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Grid extends Model
{
    use HasFactory;

    protected string $table = 'grids';
    protected $fillable = [
        'organ',
        'school',
        'serie',
        'classe',
        'subject',
        'teacher'
    ];

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class, 'id', 'organ');
    } 

    public function school():HasOne
    {
        return $this->hasOne(School::class, 'id', 'school');
    } 

    public function serie():HasOne
    {
        return $this->hasOne(Serie::class, 'id', 'serie');
    } 

    public function classe():HasOne
    {
        return $this->hasOne(Classe::class, 'id', 'classe');
    } 

    public function subject():HasOne
    {
        return $this->hasOne(Subject::class, 'id', 'subject');
    } 

    public function teacher():HasOne
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher');
    } 
}
