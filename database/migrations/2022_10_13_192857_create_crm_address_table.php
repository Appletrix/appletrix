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
        Schema::create('crm_address', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('street',80)->nullable();
            $table->integer('number')->default(0);
            $table->string('district',80)->nullable();
            $table->string('complement',80)->nullable();
            $table->string('zone',80)->nullable();
            $table->string('city',80)->nullable();
            $table->string('country',80);
            $table->string('zipcode',10);
            $table->enum('state',['Acre', 'Alagoas', 'Amapá', 'Amazonas', 'Bahia', 'Ceará', 'Distrito Federal', 'Espírito Santo', 'Goiás', 'Maranhão', 'Mato Grosso', 'Mato Grosso do Sul', 'Minas Gerais', 'Pará', 'Paraíba', 'Paraná', 'Pernambuco', 'Piauí', 'Rio de Janeiro', 'Rio Grande do Norte', 'Rio Grande do Sul', 'Rondônia', 'Roraima', 'Santa Catarina', 'São Paulo', 'Sergipe ', 'Tocantins' ])->default('São Paulo');
            $table->enum('uf',['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'])->default('SP');
            $table->enum('type',['address','street','zone'])->default('zone');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('crm_address');
    }
};


