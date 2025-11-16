<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // setores
        Schema::create('setores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('descricao');
            $table->boolean('ativo')->default(true);
        });

        // dispositivos
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->boolean('status')->default(true);
        });

        // perguntas
        Schema::create('perguntas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('texto');
            $table->boolean('status')->default(true);
            $table->boolean('resposta_numerica')->default(true);
        });

        // avaliacoes
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('setor_id');
            $table->uuid('pergunta_id');
            $table->uuid('dispositivo_id');
            $table->integer('resposta')->nullable();
            $table->text('feedback_textual')->nullable();
            $table->timestampTz('data')->useCurrent();

            $table->foreign('setor_id')->references('id')->on('setores')->onDelete('cascade');
            $table->foreign('pergunta_id')->references('id')->on('perguntas')->onDelete('cascade');
            $table->foreign('dispositivo_id')->references('id')->on('dispositivos')->onDelete('cascade');
        });

        // usuarios (administradores)
        Schema::create('usuarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('senha');
            $table->boolean('status')->default(true);
            $table->timestampTz('data_cadastro')->useCurrent();
            $table->timestampTz('data_atualizacao')->useCurrent();
        });

        // Usuário administrador inicial (login)
        DB::table('usuarios')->insert([
            'id' => Str::uuid()->toString(),
            'nome' => 'Administrador',
            'email' => 'ana.bastos@unidavi.edu.br',
            'senha' => Hash::make('admin123'),
            'status' => true,
            'data_cadastro' => now(),
            'data_atualizacao' => now(),
        ]);
    }

    public function down()
    {
    Schema::dropIfExists('usuarios');
        Schema::dropIfExists('avaliacoes');
        Schema::dropIfExists('perguntas');
        Schema::dropIfExists('dispositivos');
        Schema::dropIfExists('setores');
    }
};
