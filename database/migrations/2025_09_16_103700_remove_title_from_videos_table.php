<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'title')) {
                $table->dropColumn('title');
            }
        });
    }

    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('title')->after('id');
        });
    }
};
