<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'nome', 'status'];

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'dispositivo_id');
    }
}
