<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'descricao', 'ativo'];

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'setor_id');
    }
}
