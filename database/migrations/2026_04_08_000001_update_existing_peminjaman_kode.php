<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Peminjaman;

return new class extends Migration {
    public function up(): void
    {
        // Update existing peminjaman records that don't have kode_peminjaman
        Peminjaman::whereNull('kode_peminjaman')
            ->orWhere('kode_peminjaman', '')
            ->chunk(100, function ($peminjamans) {
                foreach ($peminjamans as $peminjaman) {
                    $peminjaman->update([
                        'kode_peminjaman' => 'PJ-' . date('Ymd', strtotime($peminjaman->created_at)) . '-' . strtoupper(substr(md5($peminjaman->id), 0, 6))
                    ]);
                }
            });
    }

    public function down(): void
    {
        // No rollback needed
    }
};