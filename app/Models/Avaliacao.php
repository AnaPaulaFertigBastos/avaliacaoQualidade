<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // using 'data' timestampTz instead

    protected $fillable = ['id', 'setor_id', 'pergunta_id', 'dispositivo_id', 'resposta', 'feedback_textual', 'data'];

    protected $dates = ['data'];

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class, 'pergunta_id');
    }

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dispositivo_id');
    }

    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setor_id');
    }
}
