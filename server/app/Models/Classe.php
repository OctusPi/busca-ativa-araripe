<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classe extends Model
{
    use HasFactory;

    protected string $table = 'classes';

    protected $fillable = [
        'organ',
        'school',
        'serie',
        'name',
        'turn',
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

    public function registration():BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public static function validateFields(?int $id = null):array
    {
        return [
            'organ'   => 'required',
            'school'  => 'required',
            'serie'   => 'required',
            'name'    => 'required',
            'turn'    => 'required',
            'status'  => 'required'
        ];
    }

    public static function validateMsg():array
    {
        return [
            'required' => 'Campo obrigatório não informado!'
        ];
    }

    public static function list_turn():array
    {
        return [
          ['id' => 0, 'title' => '']
        ];
    }
}
