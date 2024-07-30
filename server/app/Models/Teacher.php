<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    
    protected $fillable = [
        'id',
        'organ',
        'name',
        'registration',
        'cpf',
        'phone',
        'email',
        'degree',
        'qualification'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function rules(): array
    {
        return [
            'organ' => 'required',
            'name'  => 'required',
            'cpf'  => ['required', Rule::unique('teachers', 'cpf')->ignore($this->id)]
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...',
            'unique'   => 'Professor já registrado no sistema...'
        ];
    }

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class, 'id', 'organ');
    } 

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
    }

    public static function list_qualifications():array
    {
        return [
            ['id' => 1, 'title' => 'Graduação Completa'],
            ['id' => 2, 'title' => 'Graduação em Andamento'],
            ['id' => 3, 'title' => 'Especialização Completa'],
            ['id' => 4, 'title' => 'Especialização em Andamento'],
            ['id' => 5, 'title' => 'Mestrado Completo'],
            ['id' => 6, 'title' => 'Doutorado Completo'],
            ['id' => 7, 'title' => 'Doutorado em Andamento'],
        ];
    }
}
