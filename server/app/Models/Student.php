<?php

namespace App\Models;

use App\Security\Common;
use App\Utils\Dates;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'organ',
        'name',
        'birth',
        'cpf',
        'nis',
        'father',
        'mother',
        'address',
        'locality',
        'phone',
        'email',
        'status'
    ];

    public function organ():HasOne
    {
        return $this->hasOne(Organ::class, 'id', 'organ');
    }

    public function registration():BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function birth():Attribute
    {
        return Attribute::make(
            get: fn(?string $v) => Dates::convert($v, Dates::UTC, Dates::PTBR),
            set: fn(?string $v) => Dates::convert($v, Dates::PTBR, Dates::UTC)
        );
    }

    public static function list_status():array
    {
        return [
            ['id' => Common::S_ACTIVE, 'title' => 'Ativo'],
            ['id' => Common::S_NOTFOUND, 'title' => 'Não Residente'],
            ['id' => Common::S_TRANSFER, 'title' => 'Transferido']
        ];
    }
}
