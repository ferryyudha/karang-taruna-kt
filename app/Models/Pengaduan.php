<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'kode_tiket',
        'nama_pelapor',
        'phone_pelapor',
        'email_pelapor',
        'kategori',
        'lokasi',
        'judul',
        'isi_laporan',
        'foto_bukti',
        'status',
        'tanggapan',
        'foto_penanganan',
        'petugas_id',
    ];

    public static array $daftarKategori = [
        'jalan_rusak'  => 'Jalan & Fasilitas Rusak',
        'sampah'       => 'Sampah & Kebersihan',
        'drainase'     => 'Drainase & Genangan Air',
        'lampu_jalan'  => 'Penerangan Jalan (PJU)',
        'keamanan'     => 'Keamanan & Ketertiban',
        'lainnya'      => 'Lain-lain / Aspirasi',
    ];

    public static array $daftarStatus = [
        'diterima' => 'Diterima',
        'diproses' => 'Diproses',
        'selesai'  => 'Selesai',
        'ditolak'  => 'Ditolak',
    ];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function getNamaKategoriAttribute(): string
    {
        return self::$daftarKategori[$this->kategori] ?? ucfirst($this->kategori);
    }

    public static function generateKodeTiket(): string
    {
        do {
            $code = 'LAP-' . date('Ym') . '-' . strtoupper(Str::random(4));
        } while (self::where('kode_tiket', $code)->exists());

        return $code;
    }

    public function getWaPelaporLinkAttribute(): ?string
    {
        if (!$this->phone_pelapor) {
            return null;
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone_pelapor);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $pesan = "Halo Sdr/i *{$this->nama_pelapor}* 👋,\n\n"
               . "Mengenai laporan Pengaduan Warga Anda:\n"
               . "📌 *Kode Tiket:* {$this->kode_tiket}\n"
               . "🏷️ *Judul:* {$this->judul}\n"
               . "📊 *Status Saat Ini:* " . (self::$daftarStatus[$this->status] ?? $this->status) . "\n\n"
               . ($this->tanggapan ? "💬 *Tanggapan Pengurus:* {$this->tanggapan}\n\n" : "")
               . "Terima kasih telah berpartisipasi menjaga lingkungan kita! 🙏✨";

        return 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($pesan);
    }
}
