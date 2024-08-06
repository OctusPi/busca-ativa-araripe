<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grid extends Model
{
    use HasFactory;

    protected $table = 'grids';
    protected $fillable = [
        'id',
        'organ',
        'school',
        'serie',
        'classe',
        'subject',
        'teacher',
        'days'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected function casts(): array
    {
        return [
            'days' => Json::class
        ];
    }

    public function rules(): array
    {
        return [
            'organ' => 'required',
            'school' => 'required',
            'serie' => 'required',
            'classe' => 'required',
            'subject' => 'required',
            'teacher' => [
                'required',
                Rule::unique('grids', 'teacher')->where(function ($query) {
                    return $query->where([
                        ['organ', '=', $this->organ],
                        ['school', '=', $this->school],
                        ['serie', '=', $this->serie],
                        ['classe', '=', $this->classe],
                        ['subject', '=', $this->subject]
                    ]);
                })->ignore($this->id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...',
            'unique' => 'Grade já registrada no sistema...'
        ];
    }

    public static function list_days():array
    {
        return [
            ['id' => 1, 'title' => 'Segunda-feira'],
            ['id' => 2, 'title' => 'Terça-feira'],
            ['id' => 3, 'title' => 'Quarta-feira'],
            ['id' => 4, 'title' => 'Quinta-feira'],
            ['id' => 5, 'title' => 'Sexta-feira'],
        ];
    }

    public function organ(): HasOne
    {
        return $this->hasOne(Organ::class, 'id', 'organ');
    }

    public function school(): HasOne
    {
        return $this->hasOne(School::class, 'id', 'school');
    }

    public function serie(): HasOne
    {
        return $this->hasOne(Serie::class, 'id', 'serie');
    }

    public function classe(): HasOne
    {
        return $this->hasOne(Classe::class, 'id', 'classe');
    }

    public function subject(): HasOne
    {
        return $this->hasOne(Subject::class, 'id', 'subject');
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher');
    }
}
