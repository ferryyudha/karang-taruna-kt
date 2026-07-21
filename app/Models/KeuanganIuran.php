<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeuanganIuran extends Model
{
    use HasFactory;

    protected $table = 'keuangan_iuran';

    protected $fillable = [
        'anggota_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'tanggal_bayar',
        'kas_id',
        'kategori_id',
        'transaksi_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal'       => 'decimal:2',
        'bulan'         => 'integer',
        'tahun'         => 'integer',
    ];

    public static array $daftarBulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function getNamaBulanAttribute(): string
    {
        return self::$daftarBulan[$this->bulan] ?? "Bulan {$this->bulan}";
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function kas()
    {
        return $this->belongsTo(KeuanganKas::class, 'kas_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KeuanganKategori::class, 'kategori_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(KeuanganTransaksi::class, 'transaksi_id');
    }

    /**
     * Generate WhatsApp reminder URL
     */
    public function getWaLinkAttribute(): ?string
    {
        $phone = $this->anggota->phone ?? null;
        if (!$phone) {
            return null;
        }

        // Format phone number to international 62xxx
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $namaAnggota = $this->anggota->nama ?? 'Anggota';
        $bulanNama   = $this->nama_bulan;
        $tahun       = $this->tahun;
        $nominalFormatted = 'Rp ' . number_format($this->nominal, 0, ',', '.');

        $pesan = "Halo Sdr/i *{$namaAnggota}* 👋,\n\n"
               . "Pengingat dari Pengurus Karang Taruna mengenai tagihan *Iuran Kas Warga*:\n"
               . "🗓️ *Periode:* {$bulanNama} {$tahun}\n"
               . "💰 *Nominal:* {$nominalFormatted}\n"
               . "📌 *Status:* Belum Lunas\n\n"
               . "Mohon untuk dapat melakukan pembayaran iuran melalui pengurus kas. Terima kasih atas partisipasi dan dukungannya! 🙏✨";

        return 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($pesan);
    }
}
