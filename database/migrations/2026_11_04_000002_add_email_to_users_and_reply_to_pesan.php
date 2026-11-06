<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email', 100)->nullable()->unique()->after('nama');
            }
        });

        Schema::table('pesan_kontaks', function (Blueprint $table) {
            if (!Schema::hasColumn('pesan_kontaks', 'balasan')) {
                $table->text('balasan')->nullable()->after('pesan');
            }
            if (!Schema::hasColumn('pesan_kontaks', 'tanggal_balasan')) {
                $table->timestamp('tanggal_balasan')->nullable()->after('balasan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email')) {
                $table->dropColumn('email');
            }
        });

        Schema::table('pesan_kontaks', function (Blueprint $table) {
            if (Schema::hasColumn('pesan_kontaks', 'balasan')) {
                $table->dropColumn('balasan');
            }
            if (Schema::hasColumn('pesan_kontaks', 'tanggal_balasan')) {
                $table->dropColumn('tanggal_balasan');
            }
        });
    }
};

