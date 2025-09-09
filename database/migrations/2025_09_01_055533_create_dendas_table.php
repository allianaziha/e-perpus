<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengembalian_id')->constrained()->onDelete('cascade');
            $table->string('jenis')->nullable(); // bisa "telat", "rusak", "hilang" atau "telat,rusak"
            $table->integer('jumlah_hari')->nullable();
            $table->integer('dibayar')->default(0);
            $table->integer('nominal');
            $table->enum('status', ['belum lunas', 'lunas'])->default('belum lunas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
