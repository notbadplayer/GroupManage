<?php

use App\Models\Publication;
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
        Schema::create('publication_subgroup', function (Blueprint $table) {
            $table->foreignIdFor(Publication::class);
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
        Schema::dropIfExists('publication_subgroup');
    }
};
