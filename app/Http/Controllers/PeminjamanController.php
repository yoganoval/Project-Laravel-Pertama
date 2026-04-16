<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST DATA
    |--------------------------------------------------------------------------
    */
public function index()
{
    $today = Carbon::now();
    $finePerDay = 1000;

    $data = Peminjaman::with(['user', 'book'])
        ->get()
        ->map(function ($item) use ($today, $finePerDay) {

            $jatuhTempo = Carbon::parse($item->jatuh_tempo);

            // 🔥 default dari DB
            $item->is_terlambat = false;

            // 🔥 hitung keterlambatan (HANYA UNTUK DISPLAY)
            if ($item->status === 'dipinjam' && $today->gt($jatuhTempo)) {

                $item->is_terlambat = true;

                $hariTelat = $jatuhTempo->diffInDays($today);

                if ($hariTelat == 0) {
                    $hariTelat = 1;
                }

                // ⚠️ hanya untuk tampilan (tidak simpan ke DB)
                $item->denda = $hariTelat * $finePerDay;
            } else {
                // 🔥 ambil dari DB kalau sudah dikembalikan
                $item->denda = $item->denda ?? 0;
            }

            // 🔥 JANGAN override status_denda
            // biarkan dari database

            return $item;
        })
        ->values();

    return Inertia::render('Peminjaman/Index', [
        'peminjaman' => $data
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | PINJAM BUKU
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $book = Book::findOrFail($request->book_id);

        // 🔴 CEK TERLAMBAT
        $cekTerlambat = Peminjaman::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->whereDate('jatuh_tempo', '<', now())
            ->exists();

        if ($cekTerlambat) {
            return back()->with('error', 'Masih ada buku yang terlambat!');
        }

        // 🔴 CEK DENDA BELUM BAYAR
        $cekDenda = Peminjaman::where('user_id', auth()->id())
            ->where('status_denda', 'belum_bayar')
            ->where('denda', '>', 0)
            ->exists();

        if ($cekDenda) {
            return back()->with('error', 'Masih ada denda yang belum dibayar!');
        }

        // CEK STOK
        if ($book->stok <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        // CEK PINJAM GANDA
        $cek = Peminjaman::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($cek) {
            return back()->with('error', 'Buku ini masih kamu pinjam');
        }

        // SIMPAN
        Peminjaman::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'tanggal_pinjam' => now(),
            'jatuh_tempo' => now()->addDays(7),
            'status' => 'dipinjam'
        ]);

        // KURANGI STOK
        $book->decrement('stok');

        return back()->with('success', 'Buku berhasil dipinjam');
    }

    /*
    |--------------------------------------------------------------------------
    | KEMBALIKAN
    |--------------------------------------------------------------------------
    */
    public function kembali($id)
    {
        $peminjaman = Peminjaman::with('book')->findOrFail($id);

        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan');
        }

        $today = Carbon::now();
        $jatuhTempo = Carbon::parse($peminjaman->jatuh_tempo);

        $denda = 0;
        $isTerlambat = false;

        if ($today->gt($jatuhTempo)) {

            $hariTelat = $jatuhTempo->diffInDays($today);

            // 🔥 biar gak 0 walau beda jam
            if ($hariTelat == 0) {
                $hariTelat = 1;
            }

            $denda = $hariTelat * 1000;
            $isTerlambat = true;
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => $today,
            'is_terlambat' => $isTerlambat,
            'denda' => $denda,
            'status_denda' => $denda > 0 ? 'belum_bayar' : 'lunas'
        ]);

        $peminjaman->book->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE JATUH TEMPO
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jatuh_tempo' => 'required|date'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'jatuh_tempo' => $request->jatuh_tempo
        ]);

        return back()->with('success', 'Jatuh tempo berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'book'])->findOrFail($id);

        return Inertia::render('Peminjaman/Show', [
            'data' => $peminjaman
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BAYAR DENDA
    |--------------------------------------------------------------------------
    */
    public function bayarDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status_denda' => 'lunas'
        ]);

        return back()->with('success', 'Denda berhasil dibayar');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status === 'dipinjam') {
            $peminjaman->book->increment('stok');
        }

        $peminjaman->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}