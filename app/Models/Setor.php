<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * Nome da tabela no banco (plural em português).
     * Eloquent por padrão tentaria "setors" e causaria erro com PostgreSQL.
     */
    protected $table = 'setores';
    public $timestamps = false;

    protected $fillable = ['id', 'descricao', 'ativo'];

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'setor_id');
    }
}
