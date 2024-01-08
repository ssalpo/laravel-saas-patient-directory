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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('share_to_user_id')->nullable()->constrained('users');
            $table->boolean('is_share_notification_viewed')->default(false);
            $table->string('name');
            $table->string('medical_card_number');
            $table->date('birthday');
            $table->boolean('gender');
            $table->string('place_of_residence')->nullable();
            $table->string('phone')->nullable();
            $table->text('note')->nullable()->comment('заметка');
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
