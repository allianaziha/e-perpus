<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerpanjanganRequest;
use Illuminate\Http\Request;
use Alert;

class PerpanjanganController extends Controller
{
    public function index()
    {
        $perpanjanganRequests = PerpanjanganRequest::with(['peminjaman.user', 'peminjaman.buku', 'approvedBy'])
            ->latest()
            ->get();

        $title = 'Hapus Perpanjangan!';
        $text = 'Apakah anda yakin ingin menghapus data ini?';
        confirmDelete($title, $text);

        return view('admin.perpanjangan.index', compact('perpanjanganRequests'));
    }

    public function show(PerpanjanganRequest $perpanjangan)
    {
        $perpanjangan->load(['peminjaman.user', 'peminjaman.buku', 'approvedBy']);
        return view('admin.perpanjangan.show', compact('perpanjangan'));
    }

    public function approve(Request $request, PerpanjanganRequest $perpanjangan)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        if ($perpanjangan->status !== 'pending') {
            toast('Request perpanjangan sudah diproses', 'info');
            return redirect()->route('admin.perpanjangan.index');
        }

        $perpanjangan->approve(auth()->id(), $request->catatan_admin);

        toast('Request perpanjangan disetujui', 'success');
        return redirect()->route('admin.perpanjangan.index');
    }

    public function reject(Request $request, PerpanjanganRequest $perpanjangan)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        if ($perpanjangan->status !== 'pending') {
            toast('Request perpanjangan sudah diproses', 'info');
            return redirect()->route('admin.perpanjangan.index');
        }

        $perpanjangan->reject(auth()->id(), $request->catatan_admin);

        toast('Request perpanjangan ditolak', 'warning');
        return redirect()->route('admin.perpanjangan.index');
    }

    public function destroy(PerpanjanganRequest $perpanjangan)
    {
        if ($perpanjangan->status === 'approved') {
            // Jika sudah approved, rollback perubahan tanggal jatuh tempo
            $perpanjangan->peminjaman->update([
                'tgl_jatuh_tempo' => $perpanjangan->peminjaman->tgl_jatuh_tempo->subDays($perpanjangan->lama_perpanjangan),
            ]);
        }

        $perpanjangan->delete();
        Alert::error('Dihapus', 'Data perpanjangan berhasil dihapus!');
        return redirect()->route('admin.perpanjangan.index');
    }
}
