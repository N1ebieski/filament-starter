<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::create('tenants_models', function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('tenants_models');
    }
};
