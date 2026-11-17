<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTypeColumnOnStockLogs extends Migration
{
    public function up()
    {
        // Hapus kolom lama terlebih dahulu
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        // Tambahkan kolom baru dengan tipe string
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->string('type')->default('out'); // Bisa diisi 'in', 'out', atau 'correction'
        });
    }

    public function down()
    {
        // Rollback: hapus kolom string dan ganti ke enum awal
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('stock_logs', function (Blueprint $table) {
            $table->enum('type', ['in', 'out']);
        });
    }
}
