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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('subgroups', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('subgroups', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

    }
};
