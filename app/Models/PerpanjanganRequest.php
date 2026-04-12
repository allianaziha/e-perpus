<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpanjanganRequest extends Model
{
    use HasFactory;

    protected $table = 'perpanjangan_requests';

    protected $fillable = [
        'peminjaman_id',
        'lama_perpanjangan',
        'alasan',
        'status',
        'catatan_admin',
        'approved_at',
        'approved_by',
        'is_read',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper to get user through peminjaman relationship
    public function user()
    {
        return $this->peminjaman->user;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function approve($approvedBy, $catatan = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'catatan_admin' => $catatan,
            'is_read' => false,
        ]);

        // Update tanggal jatuh tempo peminjaman
        $this->peminjaman->update([
            'tgl_jatuh_tempo' => $this->peminjaman->tgl_jatuh_tempo->addDays($this->lama_perpanjangan),
        ]);
    }

    public function reject($approvedBy, $catatan = null)
    {
        $this->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'catatan_admin' => $catatan,
        ]);
    }
}
