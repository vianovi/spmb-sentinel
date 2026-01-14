<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::table('users', function (Blueprint $table) {
        // Kita pakai ENUM biar strict di level database
        // 'admin' = Staff SPMB, 'santri' = Pendaftar
        $table->enum('role', ['admin', 'santri'])->default('santri')->after('email');
        $table->string('phone', 20)->nullable()->after('role');
    });
    }

    public function down(): void
    {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role', 'phone']);
    });
    }
};
