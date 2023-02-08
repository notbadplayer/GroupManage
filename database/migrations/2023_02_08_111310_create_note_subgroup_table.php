<?php

use App\Models\Note;
use App\Models\Subgroup;
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
        Schema::create('note_subgroup', function (Blueprint $table) {
            $table->foreignIdFor(Note::class);
            $table->foreignIdFor(Subgroup::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_subgroup');
    }
};
