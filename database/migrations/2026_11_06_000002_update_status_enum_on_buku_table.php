<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `buku` MODIFY COLUMN `status` ENUM('Tersedia', 'Habis', 'Dipinjam') NOT NULL DEFAULT 'Tersedia'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `buku` MODIFY COLUMN `status` ENUM('Tersedia', 'Dipinjam') NOT NULL DEFAULT 'Tersedia'");
    }
};

