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
        'organ',
        'name',
        'registration',
        'cpf',
        'phone',
        'email',
        'qualification'
    ];

    public function rules(): array
    {
        return [
            'organ' => 'required',
            'name'  => 'required',
            'cpf'  => ['required', Rule::unique('teachers', 'cpf')->where(function($query){
                return $query->where('organ', $this->organ);
            })]
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
}
