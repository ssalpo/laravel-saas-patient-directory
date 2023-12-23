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
            $table->jsonb('case_numbers')->nullable();
            $table->tinyInteger('status')->default(\App\Enums\PatientStatusEnum::CHECKING);
            $table->string('name');
            $table->date('birthday');
            $table->boolean('gender');
            $table->dateTime('sampling_date')->comment('Дата и время забора образца');
            $table->dateTime('sample_receipt_date')->comment('Дата и время получения образца');
            $table->text('anamnes')->nullable();
            $table->jsonb('categories')->nullable();
            $table->foreignId('doctor_id')->constrained();
            $table->text('microscopic_description')->nullable()->comment('Микроскопическое описание');
            $table->text('diagnosis')->nullable()->comment('Диагноз');
            $table->text('note')->nullable()->comment('заметка');
            $table->foreignId('created_by')->constrained('users');
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
