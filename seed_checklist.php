<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\JenisLayanan;
use App\Models\AlasanPemberhentian;
use App\Models\TemplateChecklist;

DB::transaction(function () {
    TemplateChecklist::query()->delete();

    $pengangkatan = JenisLayanan::firstOrCreate(['nama' => 'Pengangkatan']);
    $rotasi = JenisLayanan::firstOrCreate(['nama' => 'Rotasi']);
    $pemberhentian = JenisLayanan::firstOrCreate(['nama' => 'Pemberhentian']);

    $alasanPurnaTugas = AlasanPemberhentian::firstOrCreate(['nama' => 'Purna Tugas']);
    $alasanMundur = AlasanPemberhentian::firstOrCreate(['nama' => 'Mengundurkan Diri']);
    $alasanDiberhentikan = AlasanPemberhentian::firstOrCreate(['nama' => 'Pelanggaran Disiplin']);

    $pengangkatanItems = [
        'Surat Pengantar dari Kecamatan',
        'Surat Usulan Rekomendasi Pengangkatan Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
        'Fc. Rekomendasi Camat terkait pengangkatan Perangkat Desa',
        'Fc. Surat Permohonan Rekomendasi Pengangkatan Perangkat Desa dari Kepala Desa kepada Camat',
        'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
        'Fc. Berita Acara musyawarah pembentukan Panitia Penjaringan dan Penyaringan',
        'Fc. Keputusan Kepala Desa tentang Panitia Penjaringan dan Penyaringan',
        'Fc. Tata Tertib Panitia Penjaringan dan Penyaringan Perangkat Desa',
        'Fc. Jadwal Pelaksanaan Penjaringan dan Penyaringan',
        'Fc. MoU Perjanjian Kerjasama (apabila tahapan seleksi menggunakan pihak ketiga)',
        'Fc. Berita Acra Penetapan calon Perangkat Desa',
        'Fc. Berita Acara Penelitian keberatan masyarakat oleh Panitia Penjaringan dan Penyaringan (apabila ada)',
        'Fc. Keputusan Kepala Desa tentang calon yang berhak mengikuti ujian',
        'Fc. Berita Acara Ujian penyaringan yang dilengkapi tanda tangan calon yang berhak mengikuti ujian',
        'Fc. Daftar hadir ujian Penyaringan dan uji kemampuan',
        'Fc. Berita Acara uji kemampuan yang dilengkapi tanda tangan calon yang berhak mengikuti uji kemampuan',
        'Fc. Berita Acara penetapan calon yang lulus dan memperoleh peringkat 1,2 dan 3',
        'Fc. Berita Acara ujian penyaringan lanjutan (apabila terdapat lebih dari satu orang calon yang lulus dan memperoleh nilai tertinggi yang sama)',
        'Fc. Rincian perhitungan penilaian penjaringan dan penyaringan',
        'Fc. Persyaratan administrasi calon yang lolos pada seleksi pengangkatan perangkat desa',
        'Fc. Rencana Anggaran Biaya (RAB)',
        'Fc. Laporan pertanggungjawaban penggunaan anggaran',
        'Dokumentasi setiap tahapan penjaringan Perangkat Desa',
    ];

    $rotasiItems = [
        'Surat Pengantar dari Kecamatan',
        'Surat Usulan Rekomendasi Rotasi Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
        'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
        'Fc. Berita Acara Konsultasi Kepala Desa kepada Camat terkait akan dilaksanakannya rotasi Perangkat Desa',
        'Fc. Surat Pemberitahuan dari Kepala desa kepada BPD mengenai pelaksaaan rotasi',
        'Fc. Hasil Rincian perhitungan penilaian kinerja Perangkat desa yang akan dirotasi',
        'Fc. Berita Acara hasil penilaian kinerjaperangkat desa yang akan dirotasi',
        'Fc. Surat Permohonan Rekomendasi Rotasi Perangkat Desa dari Kepala Desa kepada Camat',
        'Fc. Rekomendasi Camat atas Proses Rotasi',
        'Fc. Sk Pengangkatan Pertama Perangkat Desa yang akan di Rotasi',
        'Fc. Ijasah atau Surat Tanda Tamat Belajar (STTB)',
        'Fc. Daftar Hadir Perangkat Desa yang akan dirotasi selama 6 (enam) bulan sebelum pelaksaaan Rotasi',
        'Fc. Peraturan Kepala Desa tentang Tata Tertib Rotasi',
    ];

    $purnaTugasItems = [
        'Surat Pengantar dari Kecamatan',
        'Surat Usulan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
        'Fc. Surat Permohonan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Camat',
        'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
        'Fc. Rekomendasi Camat atas proses pemberhentian Perangkat Desa',
        'Fc. SK Pengangkatan Perangkat Desa',
        'Fc. Kartu Keluarga',
        'Fc. KTP atau Akta Kelahiran',
    ];

    $permintaanSendiriItems = array_merge($purnaTugasItems, [
        'Fc. Surat Pernyataan Pengunduran diri dari Perangkat Desa yang ditujukan kepada Kepala Desa'
    ]);

    $diberhentikanItems = array_merge($purnaTugasItems, [
        'Fc. Bukti teguran lisan tercatat pertama, Teguran tertulis pertama, Teguran tertulis kedua, Teguran tertulis ketiga',
        'Fc. SK pemberhentian Sementara',
        'Fc. Berita Acara Pemeriksaan',
        'Fc. Berita Acara Hasil Rapat Tim Pemeriksa Pelanggaran Disiplin',
        'Fc. Laporan Hasil Pemeriksaan dari Ketua TIM Pemeriksa Pelanggaran Disiplin',
        'Fc. Surat Pemberitahuan Penjatuhan Disiplin dari Kepala Desa kepada Bupati lewat Camat',
        'Fc. Surat Keputusan Penjatuhan Hukuman Disiplin dari Kepala Desa',
        'Fc. SK penguatan Hukuman Disiplin dari Kepala Desa (apabila menolak keberatan yang diajukan Perangkat desa yang bersangkutan)',
        'Fc. Kartu Hukuman Disiplin',
    ]);

    $createTemplates = function ($items, $jenis_layanan_id, $alasan_id = null) {
        foreach ($items as $idx => $item) {
            $wajib = (str_contains($item, '(apabila') || str_contains($item, 'apabila')) ? false : true;
            TemplateChecklist::create([
                'jenis_layanan_id' => $jenis_layanan_id,
                'alasan_pemberhentian_id' => $alasan_id,
                'nama_dokumen' => $item,
                'urutan' => $idx + 1,
                'wajib' => $wajib,
            ]);
        }
    };

    $createTemplates($pengangkatanItems, $pengangkatan->id);
    $createTemplates($rotasiItems, $rotasi->id);
    $createTemplates($purnaTugasItems, $pemberhentian->id, $alasanPurnaTugas->id);
    $createTemplates($permintaanSendiriItems, $pemberhentian->id, $alasanMundur->id);
    $createTemplates($diberhentikanItems, $pemberhentian->id, $alasanDiberhentikan->id);
});

echo "SUCCESS";
