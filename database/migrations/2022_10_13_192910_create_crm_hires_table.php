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
        Schema::create('crm_hires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->constrained('crm_address');
            $table->uuid('uuid');
            $table->string('name', 150);
            $table->string('slug');
            $table->tinyText('description')->nullable();
            $table->string('document', 15);
            $table->string('billing_company_code', 45);
            $table->tinyInteger('status');
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
        Schema::dropIfExists('crm_hires');
    }
};
