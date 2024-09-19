<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `roles` ADD FULLTEXT `fulltext_index` (`name`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->dropIndex('fulltext_index');
        });
    }
};
