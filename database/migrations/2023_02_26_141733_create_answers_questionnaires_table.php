<?php

use App\Models\Answer;
use App\Models\Questionnaire;
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
        Schema::create('answer_questionnaire', function (Blueprint $table) {
            $table->foreignIdFor(Answer::class);
            $table->foreignIdFor(Questionnaire::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_questionnaire');
    }
};
