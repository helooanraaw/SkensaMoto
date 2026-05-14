<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->text('catatan_kerusakan')->nullable();
            $table->string('quotation_status')->nullable(); // 'sent', 'approved', 'rejected'
        });

        Schema::table('pemakaian_barang', function (Blueprint $table) {
            $table->boolean('is_approved')->default(true); // User can uncheck this
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn('catatan_kerusakan');
            $table->dropColumn('quotation_status');
        });

        Schema::table('pemakaian_barang', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
