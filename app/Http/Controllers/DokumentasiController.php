<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $kegiatanList = Kegiatan::orderBy('tanggal', 'desc')->get();
        $selectedKegiatan = null;
        $dokumentasi = collect();

        if ($request->has('kegiatan_id')) {
            $selectedKegiatan = Kegiatan::findOrFail($request->kegiatan_id);
            $dokumentasi = Dokumentasi::where('kegiatan_id', $request->kegiatan_id)
                ->latest()->paginate(12);
        } else {
            $dokumentasi = Dokumentasi::with('kegiatan')->latest()->paginate(12);
        }

        return view('admin.dokumentasi.index', compact('kegiatanList', 'selectedKegiatan', 'dokumentasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'foto'        => 'required|array',
            'foto.*'      => 'file|mimes:jpg,jpeg,png,webp,zip|max:20480',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $uploadedCount = 0;

        foreach ($request->file('foto') as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            
            if ($extension === 'zip') {
                $zip = new \ZipArchive;
                if ($zip->open($file->getRealPath()) === TRUE) {
                    $maxFilesPerZip = 100; // batas biar tidak jadi "zip bomb" (ribuan file sekaligus)
                    $processedFromThisZip = 0;

                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        if ($processedFromThisZip >= $maxFilesPerZip) {
                            break;
                        }

                        $filename = $zip->getNameIndex($i);
                        $fileInfo = pathinfo($filename);
                        
                        // Abaikan folder kosong atau file metadata macOS (__MACOSX)
                        if (empty($fileInfo['basename']) || str_contains($filename, '__MACOSX')) {
                            continue;
                        }
                        
                        $ext = strtolower($fileInfo['extension'] ?? '');
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $content = $zip->getFromIndex($i);

                            // Cek dimensi dari header dulu (murah) sebelum di-decode penuh,
                            // supaya gambar dengan resolusi ekstrem langsung dilewati.
                            $dimensions = @getimagesizefromstring($content);
                            if ($dimensions === false || $dimensions[0] > 6000 || $dimensions[1] > 6000) {
                                continue;
                            }

                            // Auto-resize extracted image from ZIP
                            $filename = Str::uuid() . '.' . ($ext === 'jpeg' ? 'jpg' : $ext);
                            $newPath = 'dokumentasi/' . $filename;

                            $image = Image::read($content);
                            if ($image->width() > 1920 || $image->height() > 1080) {
                                $image->scaleDown(1920, 1080);
                            }
                            $encoded = match ($ext) {
                                'png'  => $image->toPng(),
                                'webp' => $image->toWebp(80),
                                default => $image->toJpeg(80),
                            };
                            Storage::disk('public')->put($newPath, (string) $encoded);

                            Dokumentasi::create([
                                'kegiatan_id' => $request->kegiatan_id,
                                'foto'        => $newPath,
                                'keterangan'  => $request->keterangan,
                            ]);
                            $uploadedCount++;
                            $processedFromThisZip++;
                        }
                    }
                    $zip->close();
                } else {
                    return back()->with('error', 'Gagal mengekstrak file ZIP.');
                }
            } else {
                Dokumentasi::create([
                    'kegiatan_id' => $request->kegiatan_id,
                    'foto'        => ImageUploadService::uploadPhoto($file, 'dokumentasi'),
                    'keterangan'  => $request->keterangan,
                ]);
                $uploadedCount++;
            }
        }

        return redirect()->route('admin.dokumentasi.index', ['kegiatan_id' => $request->kegiatan_id])
            ->with('success', $uploadedCount . ' Foto dokumentasi berhasil ditambahkan!');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        $kegiatanId = $dokumentasi->kegiatan_id;
        Storage::disk('public')->delete($dokumentasi->foto);
        $dokumentasi->delete();

        return redirect()->route('admin.dokumentasi.index', ['kegiatan_id' => $kegiatanId])
            ->with('success', 'Foto berhasil dihapus!');
    }
}
