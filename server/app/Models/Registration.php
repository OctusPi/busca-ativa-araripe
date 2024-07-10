<?php

namespace App\Models;

use App\Security\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'students_registrations';

    protected $fillable = [
        'organ',
        'school',
        'serie',
        'classe',
        'student',
        'year',
        'status'
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

    public function student():HasOne
    {
        return $this->hasOne(Student::class, 'id', 'student');
    }

    public static function list_status():array
    {
        return [
            ['id' => Common::S_ACTIVE, 'title' => 'Cursando'],
            ['id' => Common::S_APPROVED, 'title' => 'Aprovado'],
            ['id' => Common::S_INACTIVE, 'title' => 'Desistente'],
            ['id' => Common::S_TRANSFER, 'title' => 'Transferido'],
            ['id' => Common::S_DISAPPROVED, 'title' => 'Reprovado']
        ];
    }
}
