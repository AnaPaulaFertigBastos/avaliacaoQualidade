<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        $now = now();

        DB::table('perguntas')->insert([
            ['id' => Str::uuid()->toString(), 'texto' => 'Como você avalia o atendimento recebido?', 'status' => true, 'resposta_numerica' => true],
            ['id' => Str::uuid()->toString(), 'texto' => 'O tempo de espera para ser atendido foi satisfatório?', 'status' => true, 'resposta_numerica' => true],
            ['id' => Str::uuid()->toString(), 'texto' => 'O funcionário demonstrou cordialidade e profissionalismo?', 'status' => true, 'resposta_numerica' => true],
            ['id' => Str::uuid()->toString(), 'texto' => 'O ambiente estava limpo e organizado?', 'status' => true, 'resposta_numerica' => true],
            ['id' => Str::uuid()->toString(), 'texto' => 'Você recomendaria nossos serviços a outras pessoas?', 'status' => true, 'resposta_numerica' => true],
            ['id' => Str::uuid()->toString(), 'texto' => 'Deixe aqui seu feedback adicional (opcional):', 'status' => true, 'resposta_numerica' => false],
        ]);
    }

    public function down()
    {
        $texts = [
            'Como você avalia o atendimento recebido?',
            'O tempo de espera para ser atendido foi satisfatório?',
            'O funcionário demonstrou cordialidade e profissionalismo?',
            'O ambiente estava limpo e organizado?',
            'Você recomendaria nossos serviços a outras pessoas?',
            'Deixe aqui seu feedback adicional (opcional):',
        ];

        DB::table('perguntas')->whereIn('texto', $texts)->delete();
    }
};
