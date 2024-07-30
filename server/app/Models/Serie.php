<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    use HasFactory;

    const S_ACTIVE = 1;
    const S_INACTIVE = 0;

    protected $table = 'series';

    protected $fillable = [
        'id',
        'organ',
        'code',
        'name',
        'course',
        'level',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function rules(): array
    {
        return [
            'organ'   => 'required',
            'code'    => ['required', Rule::unique('series')->ignore($this->id)],
            'name'    => 'required',
            'course'  => 'required',
            'level'   => 'required',
            'status'  => 'required' 
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...',
            'unique' => 'Série já registrado no sistema...'
        ];
    }

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class,'id','organ');
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
    }

    public static function list_courses():array{
        return [
            ['id' => 1, 'title' => 'Alfabetização'],
            ['id' => 2, 'title' => 'Fundamental I'],
            ['id' => 3, 'title' => 'Fundamental II'],
            ['id' => 4, 'title' => 'Ensino Médio Regular'],
            ['id' => 5, 'title' => 'Ensino Médio Especial'],
            ['id' => 6, 'title' => 'Graduação/Especialização/PHD'],
            ['id' => 7, 'title' => 'Outro']
        ];
    }

    public static function list_levels():array{
        return [
            ['id' => 1, 'title' => 'Infantil'],
            ['id' => 2, 'title' => 'Fundamental'],
            ['id' => 3, 'title' => 'Médio'],
            ['id' => 4, 'title' => 'Superior'],
            ['id' => 5, 'title' => 'Outro']
        ];
    }

    public static function list_status():array{
        return [
            ['id' => self::S_ACTIVE, 'title' => 'Ativo'],
            ['id' => self::S_INACTIVE, 'title' => 'Inativo'],
        ];
    }
}
