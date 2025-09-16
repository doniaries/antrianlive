<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->enum('status', ['buka', 'tutup', 'istirahat'])->default('buka')->after('name');
            $table->time('open_time')->nullable()->after('status');
            $table->time('close_time')->nullable()->after('open_time');
        });
    }

    public function down()
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->dropColumn(['status', 'open_time', 'close_time']);
        });
    }
};
