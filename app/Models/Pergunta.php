<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'texto', 'status', 'resposta_numerica'];

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'pergunta_id');
    }
}
