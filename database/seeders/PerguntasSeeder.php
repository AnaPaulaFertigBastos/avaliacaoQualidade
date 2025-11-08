<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PerguntasSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('perguntas')->insert([
            ['id' => Str::uuid()->toString(), 'texto' => 'Como você avalia o atendimento recebido?', 'status' => true, 'resposta_numerica' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid()->toString(), 'texto' => 'O tempo de espera para ser atendido foi satisfatório?', 'status' => true, 'resposta_numerica' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid()->toString(), 'texto' => 'O funcionário demonstrou cordialidade e profissionalismo?', 'status' => true, 'resposta_numerica' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid()->toString(), 'texto' => 'O ambiente estava limpo e organizado?', 'status' => true, 'resposta_numerica' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid()->toString(), 'texto' => 'Você recomendaria nossos serviços a outras pessoas?', 'status' => true, 'resposta_numerica' => true, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid()->toString(), 'texto' => 'Deixe aqui seu feedback adicional (opcional):', 'status' => true, 'resposta_numerica' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
