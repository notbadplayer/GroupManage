<?php

use App\Models\Event;
use App\Models\Group;
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
        Schema::create('event_group', function (Blueprint $table) {
            $table->foreignIdFor(Event::class);
            $table->foreignIdFor(Group::class);
        });

        Schema::create('event_subgroup', function (Blueprint $table) {
            $table->foreignIdFor(Event::class);
            $table->foreignIdFor(Subgroup::class);
        });

        Schema::create('event_user', function (Blueprint $table) {
            $table->foreignIdFor(Event::class);
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
        Schema::dropIfExists('event_group');
        Schema::dropIfExists('event_subgroup');
        Schema::dropIfExists('event_user');
    }
};
