<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up(): void
{
    Schema::table('surveys', function (Blueprint $table) {

        $table->foreignId('jadwal_id')->nullable();
        $table->integer('rating')->nullable();
        $table->text('komentar')->nullable();

    });
}

public function down(): void
{
    Schema::table('surveys', function (Blueprint $table) {

        $table->dropColumn(['jadwal_id','rating','komentar']);

    });
}

};