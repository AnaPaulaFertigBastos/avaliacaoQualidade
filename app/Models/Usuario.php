<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'usuarios';

    protected $fillable = ['id', 'nome', 'email', 'senha', 'status'];
    protected $hidden = ['senha'];

    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';

    public function getAuthPassword()
    {
        return $this->senha;
    }
}
