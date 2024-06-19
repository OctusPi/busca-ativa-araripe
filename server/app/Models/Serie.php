<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Serie extends Model
{
    use HasFactory;

    protected string $table = 'series';

    protected $fillable = [
        'organ',
        'code',
        'name',
        'course',
        'level',
        'status'
    ];

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class, 'id', 'organ');
    }

    public function classe():BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public static function validateFields(?int $id = null):array
    {
        return [
            'organ'   => 'required',
            'code'    => ['required', Rule::unique('series')->ignore($id)],
            'name'    => 'required',
            'course'  => 'required',
            'level'   => 'required',
            'status'  => 'required' 
        ];
    }

    public static function validateMsg():array
    {
        return [
            'required' => 'Campo obrigatório não informado!',
            'unique'   => 'Orgão já registrado no sistema!'
        ];
    }

    public static function list_courses():array{
        return [
            ['id' => 1, 'title' => '']
        ];
    }

    public static function list_levels():array{
        return [
            ['id' => 1, 'title' => '']
        ];
    }

    public static function list_status():array{
        return [
            ['id' => 1, 'title' => '']
        ];
    }
}
