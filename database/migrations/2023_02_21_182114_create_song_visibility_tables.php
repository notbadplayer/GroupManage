<?php

use App\Models\Group;
use App\Models\Song;
use App\Models\Subgroup;
use App\Models\User;
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
        Schema::create('group_song', function (Blueprint $table) {
            $table->foreignIdFor(Group::class);
            $table->foreignIdFor(Song::class);
        });

        Schema::create('song_subgroup', function (Blueprint $table) {
            $table->foreignIdFor(Song::class);
            $table->foreignIdFor(Subgroup::class);
        });

        Schema::create('song_user', function (Blueprint $table) {
            $table->foreignIdFor(Song::class);
            $table->foreignIdFor(User::class);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_song');
        Schema::dropIfExists('song_subgroup');
        Schema::dropIfExists('song_user');

    }
};
