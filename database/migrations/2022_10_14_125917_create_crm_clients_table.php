<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('crm_files_loads');
            $table->uuid('uuid')->index()->unique()->default('uuid()');
            $table->string('hash')->index();
            $table->string('title',10);
            $table->string('name',80);
            $table->string('slug',80);
            $table->string('registry')->nullable();
            $table->string('document',15)->index();
            $table->enum('type_entity',['cpf','cnpj'])->default('cpf');
            $table->enum('status',['ativo','inativo'])->index()->default('ativo');
            $table->date('birthdate')->nullable();
            $table->dateTime('entrydate')->nullable();
            $table->string('gender',45)->nullable();
            $table->string('matritalstatus')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_client');
    }
};
