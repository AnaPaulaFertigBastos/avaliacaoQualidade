<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        $now = now();

        $setores = [
            ['id' => Str::uuid()->toString(), 'descricao' => 'Recepção', 'ativo' => true],
            ['id' => Str::uuid()->toString(), 'descricao' => 'Vendas', 'ativo' => true],
            ['id' => Str::uuid()->toString(), 'descricao' => 'Caixa', 'ativo' => true],
            ['id' => Str::uuid()->toString(), 'descricao' => 'Estacionamento', 'ativo' => true],
            ['id' => Str::uuid()->toString(), 'descricao' => 'Banheiro', 'ativo' => true],
        ];

        DB::table('setores')->insert($setores);

        $dispositivos = [
            ['id' => Str::uuid()->toString(), 'nome' => 'Tablet Samsung', 'status' => true],
            ['id' => Str::uuid()->toString(), 'nome' => 'Tablet Asus', 'status' => true],
            ['id' => Str::uuid()->toString(), 'nome' => 'Tablet Samsung', 'status' => true],
            ['id' => Str::uuid()->toString(), 'nome' => 'Tablet Samsung', 'status' => true],
            ['id' => Str::uuid()->toString(), 'nome' => 'Tablet Asus', 'status' => true],
        ];

        DB::table('dispositivos')->insert($dispositivos);
    }

    public function down()
    {
        $setores = ['Recepção', 'Vendas', 'Caixa', 'Estacionamento', 'Banheiro'];
        DB::table('setores')->whereIn('descricao', $setores)->delete();

        $dispositivos = ['Recepção - Tablet 1', 'Vendas - Tablet 1', 'Caixa - Tablet 1', 'Estacionamento - Tablet 1', 'Banheiro - Tablet 1'];
        DB::table('dispositivos')->whereIn('nome', $dispositivos)->delete();
    }
};
