<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::create('tenants_models', function (Blueprint $table) {
            $table->bigInteger('tenant_id')->unsigned();
            $table->bigInteger('authenticatable_id')->unsigned();
            $table->string('authenticatable_type');
            $table->index(['authenticatable_type', 'authenticatable_id']);

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->unique(['tenant_id', 'authenticatable_type', 'authenticatable_id'], 'tenants_models_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('tenants_models');
    }
};
