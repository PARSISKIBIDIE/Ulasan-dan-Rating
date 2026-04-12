<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('day_releases', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->boolean('released')->default(false);
            $table->timestamps();
        });

        // Seed default days (not released)
        $now = now();
        DB::table('day_releases')->insert([
            ['day' => 'Senin', 'released' => false, 'created_at' => $now, 'updated_at' => $now],
            ['day' => 'Selasa', 'released' => false, 'created_at' => $now, 'updated_at' => $now],
            ['day' => 'Rabu', 'released' => false, 'created_at' => $now, 'updated_at' => $now],
            ['day' => 'Kamis', 'released' => false, 'created_at' => $now, 'updated_at' => $now],
            ['day' => 'Jumat', 'released' => false, 'created_at' => $now, 'updated_at' => $now],
            ['day' => 'Sabtu', 'released' => false, 'created_at' => $now, 'updated_at' => $now],
            ['day' => 'Minggu', 'released' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_releases');
    }
};
