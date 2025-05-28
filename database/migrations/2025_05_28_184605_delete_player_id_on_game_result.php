<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game_results', function (Blueprint $table) {
            // 1. Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['player_id']); // Menghapus constraint foreign key
            
            // 2. Setelah constraint dihapus, baru hapus kolom
            $table->dropColumn('player_id'); // Menghapus kolom player_id
        });
    }

    /**
     * Reverse the migrations (jika ingin rollback).
     */
    public function down(): void
    {
        Schema::table('game_results', function (Blueprint $table) {
            // Tambahkan kembali kolom player_id
            $table->unsignedBigInteger('player_id')->nullable(); // Menambahkan kolom player_id
            
            // Tambahkan kembali foreign key constraint
            $table->foreign('player_id')
                  ->references('id') // Kolom referensi di tabel players
                  ->on('players')    // Tabel yang direferensikan
                  ->onDelete('cascade'); // Aksi onDelete
        });
    }
};
