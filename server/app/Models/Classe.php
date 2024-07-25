<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'id',
        'organ',
        'school',
        'serie',
        'name',
        'turn',
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
            'school'  => 'required',
            'serie'   => 'required',
            'name'    => 'required',
            'turn'    => 'required',
            'status'  => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Campo obrigatório não informado...'
        ];
    }

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class,'id','organ');
    }

    public function school():HasOne
    {
        return $this->hasOne(School::class,'id','school');
    }

    public function serie():HasOne
    {
        return $this->hasOne(Serie::class,'id','serie');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function grid():BelongsTo
    {
        return $this->belongsTo(Grid::class);
    }

    public static function list_turn():array
    {
        return [
          ['id' => 0, 'title' => '']
        ];
    }
}
