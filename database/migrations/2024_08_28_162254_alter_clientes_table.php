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
        Schema::table('clientes', function (Blueprint $table) {
            $table->json('bancarias')->nullable();
            $table->json('funcionais')->nullable();
            $table->json('residenciais')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
           $table->dropColumn('residenciais');
           $table->dropColumn('funcionais');
           $table->dropColumn('bancarias');
        });
    }
};
