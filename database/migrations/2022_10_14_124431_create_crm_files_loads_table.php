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
        Schema::create('crm_files_loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hires_id')->constrained('crm_hires');
            $table->uuid('uuid');
            $table->string('name',80)->nullable();
            $table->string('slug',80)->nullable();
            $table->string('file')->nullable();
            $table->enum('operation',['REMESSA','RETIRAR'])->default('REMESSA');
            $table->integer('remittances')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('clientlist')->default(0);
            $table->integer('client_details')->default(0);
            $table->decimal('amount',8,2)->default(0);
            $table->integer('whitelist')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->dateTime('movement_date')->useCurrent();
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
        Schema::dropIfExists('crm_files_loads');
    }
};
