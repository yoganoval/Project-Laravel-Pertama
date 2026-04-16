<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'jatuh_tempo',
        'tanggal_kembali',
        'status',
        'denda',
        'status_denda' // 🔥 WAJIB
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'jatuh_tempo' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    // cek terlambat
    public function isTerlambat()
    {
        return $this->status === 'dipinjam' && now()->gt($this->jatuh_tempo);
    }
}