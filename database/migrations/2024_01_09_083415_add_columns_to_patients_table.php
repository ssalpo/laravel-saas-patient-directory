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
        Schema::table('patients', function (Blueprint $table) {
            $table->text('morbi')->nullable();
            $table->text('vitae')->nullable();
            $table->text('lab_workup')->nullable();
            $table->text('diagnosis')->nullable();
            $table->string('mkb')->nullable();
            $table->text('treatment')->nullable();
            $table->text('stain')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'morbi',
                'vitae',
                'lab_workup',
                'diagnosis',
                'mkb',
                'treatment',
            ]);
        });
    }
};
