<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breezy_sessions', function (
            Blueprint $table
        ): void {
            $table->id();
            $table->string('authenticatable_type');
            $table->unsignedBigInteger('authenticatable_id');
            $table->string('panel_id')->nullable();
            $table->string('guard')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('two_factor_secret')
                ->nullable();
            $table->text('two_factor_recovery_codes')
                ->nullable();
            $table->timestamp('two_factor_confirmed_at')
                ->nullable();
            $table->timestamps();

            $table->foreign('authenticatable_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breezy_sessions');
    }
};
