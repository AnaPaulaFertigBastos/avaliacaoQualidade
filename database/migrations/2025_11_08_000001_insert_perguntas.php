<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // Insert perguntas with fixed UUIDs so other parts of the app can reference them reliably
        DB::table('perguntas')->insert([
            ['id' => '2f9e1b0a-8c2d-4f3a-9d2b-1a2b3c4d5e01', 'texto' => 'Como você avalia o atendimento recebido?', 'status' => true, 'resposta_numerica' => true, 'ordem' => 1],
            ['id' => '3b8c2f1d-1a2b-4c3d-8e9f-2b3c4d5e6f02', 'texto' => 'O tempo de espera para ser atendido foi satisfatório?', 'status' => true, 'resposta_numerica' => true, 'ordem' => 2],
            ['id' => '4c7d3e2b-2b3c-4d5e-9f8a-3c4d5e6f7083', 'texto' => 'O funcionário demonstrou cordialidade e profissionalismo?', 'status' => true, 'resposta_numerica' => true, 'ordem' => 3],
            ['id' => '5d6e4f3c-3c4d-4e6f-8a1b-4d5e6f708194', 'texto' => 'O ambiente estava limpo e organizado?', 'status' => true, 'resposta_numerica' => true, 'ordem' => 4],
            ['id' => '6e7f5a4b-4d5e-4f7a-9b2c-5e6f70819505', 'texto' => 'Você recomendaria nossos serviços a outras pessoas?', 'status' => true, 'resposta_numerica' => true, 'ordem' => 5],
            ['id' => '7f8a6b5c-5e6f-4a8b-8c3d-6f7081950616', 'texto' => 'Deixe aqui seu feedback adicional (opcional):', 'status' => true, 'resposta_numerica' => false, 'ordem' => 6],
        ]);
    }

    public function down()
    {
        $ids = [
            '2f9e1b0a-8c2d-4f3a-9d2b-1a2b3c4d5e01',
            '3b8c2f1d-1a2b-4c3d-8e9f-2b3c4d5e6f02',
            '4c7d3e2b-2b3c-4d5e-9f8a-3c4d5e6f7083',
            '5d6e4f3c-3c4d-4e6f-8a1b-4d5e6f708194',
            '6e7f5a4b-4d5e-4f7a-9b2c-5e6f70819505',
            '7f8a6b5c-5e6f-4a8b-8c3d-6f7081950616',
        ];

        DB::table('perguntas')->whereIn('id', $ids)->delete();
    }
};
