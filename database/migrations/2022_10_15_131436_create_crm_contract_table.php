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
        Schema::create('crm_contracts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index()->unique()->default('uuid()');
            $table->foreignId('client_id')->constrained('crm_clients');
            $table->string('document',15)->index();
            $table->string('hash')->index();
            $table->string('contract')->index();
            $table->string('referencedocs')->index();
            $table->string('documentfica')->nullable();
            $table->dateTime('duedate')->index();
            $table->decimal('amount', 8,2)->default(0);
            $table->integer('delayedday')->default(0);
            $table->string('score',45)->nullable();
            $table->string('shipping_status')->nullable();
            $table->string('shipping_reason')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->foreign('document')->references('document')->on('crm_clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_contract');
    }
};
