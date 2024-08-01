<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;

    const S_INACTIVE = 0;
    const S_ACTIVE = 1;
    const S_APPROVED = 2;
    const S_TRANSFER = 3;
    const S_DISAPPROVED = 4;

    protected $table = 'students_registrations';

    protected $fillable = [
        'id',
        'organ',
        'school',
        'serie',
        'classe',
        'student',
        'year',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function rules(): array
    {
        return [
            'organ' => 'required',
            'school'  => 'required',
            'serie' => 'required',
            'classe'=> 'required',
            'year'=> 'required',
            'status'=> 'required',
            'student'  => ['required', Rule::unique('students_registrations', 'student')->where(function($query){
                return $query->where('year', $this->year);
            })->ignore($this->id)]
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...',
            'unique' => 'Aluno já matriculado para o ano letivo informado...',
        ];
    }

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
            ['id' => self::S_ACTIVE, 'title' => 'Matriculado'],
            ['id' => self::S_APPROVED, 'title' => 'Aprovado'],
            ['id' => self::S_INACTIVE, 'title' => 'Desistente'],
            ['id' => self::S_TRANSFER, 'title' => 'Transferido'],
            ['id' => self::S_DISAPPROVED, 'title' => 'Reprovado']
        ];
    }
}
