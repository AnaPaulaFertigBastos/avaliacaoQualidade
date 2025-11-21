<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // Insert setores and dispositivos with fixed UUIDs
        $setores = [
            ['id' => '8a1b2c3d-1e2f-4a3b-9c4d-7e8f90123456', 'descricao' => 'Recepção', 'ativo' => true],
            ['id' => '9b2c3d4e-2f3a-4b5c-8d6e-8f9012345678', 'descricao' => 'Vendas', 'ativo' => true],
            ['id' => 'aa3d4e5f-3a4b-4c6d-9e7f-901234567890', 'descricao' => 'Caixa', 'ativo' => true],
            ['id' => 'bb4e5f6a-4b5c-4d7e-8f8a-0123456789ab', 'descricao' => 'Estacionamento', 'ativo' => true],
            ['id' => 'cc5f6a7b-5c6d-4e8f-9a9b-1234567890bc', 'descricao' => 'Banheiro', 'ativo' => true],
        ];

        DB::table('setores')->insert($setores);

        $dispositivos = [
            ['id' => 'd1e2f3a4-6b7c-4f9a-8b1c-234567890cde', 'nome' => 'Tablet Samsung', 'status' => true],
            ['id' => 'e2f3a4b5-7c8d-4a0b-9c2d-34567890def1', 'nome' => 'Tablet Asus', 'status' => true],
            ['id' => 'f3a4b5c6-8d9e-4b1c-8d3e-4567890ef123', 'nome' => 'Tablet Samsung', 'status' => true],
            ['id' => 'a4b5c6d7-9e0f-4c2d-9e4f-567890f12345', 'nome' => 'Tablet Samsung', 'status' => true],
            ['id' => 'b5c6d7e8-0f1a-4d3e-8f5a-67890f123456', 'nome' => 'Tablet Asus', 'status' => true],
        ];

        DB::table('dispositivos')->insert($dispositivos);
    }

    public function down()
    {
        $setorIds = [
            '8a1b2c3d-1e2f-4a3b-9c4d-7e8f90123456',
            '9b2c3d4e-2f3a-4b5c-8d6e-8f9012345678',
            'aa3d4e5f-3a4b-4c6d-9e7f-901234567890',
            'bb4e5f6a-4b5c-4d7e-8f8a-0123456789ab',
            'cc5f6a7b-5c6d-4e8f-9a9b-1234567890bc',
        ];
        DB::table('setores')->whereIn('id', $setorIds)->delete();

        $dispositivoIds = [
            'd1e2f3a4-6b7c-4f9a-8b1c-234567890cde',
            'e2f3a4b5-7c8d-4a0b-9c2d-34567890def1',
            'f3a4b5c6-8d9e-4b1c-8d3e-4567890ef123',
            'a4b5c6d7-9e0f-4c2d-9e4f-567890f12345',
            'b5c6d7e8-0f1a-4d3e-8f5a-67890f123456',
        ];
        DB::table('dispositivos')->whereIn('id', $dispositivoIds)->delete();
    }
};
