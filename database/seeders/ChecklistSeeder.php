<?php

namespace Database\Seeders;

use App\Models\Ajuan;
use App\Models\AlasanPemberhentian;
use App\Models\JenisLayanan;
use App\Models\TemplateChecklist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // Delete all to avoid duplicates or old mapping
            TemplateChecklist::query()->delete();
            AlasanPemberhentian::query()->delete();

            $pengangkatan = JenisLayanan::firstOrCreate(['nama' => 'Pengangkatan']);
            $rotasi = JenisLayanan::firstOrCreate(['nama' => 'Rotasi']);
            $pemberhentian = JenisLayanan::firstOrCreate(['nama' => 'Pemberhentian']);

            $alasanPurnaTugas = AlasanPemberhentian::firstOrCreate(['nama' => 'Purna Tugas']);
            $alasanMundur = AlasanPemberhentian::firstOrCreate(['nama' => 'Permintaan Sendiri']);
            $alasanDiberhentikan = AlasanPemberhentian::firstOrCreate(['nama' => 'Diberhentikan']);

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
                'Fc. Surat Pernyataan Pengunduran diri dari Perangkat Desa yang ditujukan kepada Kepala Desa',
            ]);

            $diberhentikanItems = [
                'Surat Pengantar dari Kecamatan',
                'Surat Usulan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
                'Fc. Surat Permohonan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Camat',
                'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
                'Fc. Rekomendasi Camat atas proses pemberhentian Perangkat Desa',
                'Fc. SK Pengangkatan Perangkat Desa',
                'Fc. Kartu Keluarga',
                'Fc. Surat keterangan Kematian (apabila meninggal)',
                'Fc. Surat Keterangan dari Rumah Sakit yang menerangkan bahwa yang bersangkutan tidak dapat melaksanakan tugas dan kewajiban secara berturut-turut selama 6 (enam) bulan (apabila berhalangan tetap)',
                'Fc. Putusan dari pengadilan yang memiliki kekuatan hukum tetap (apabila melakukan Tindak Pidana)',
                'Fc. Bukti teguran lisan tercatat pertama, Teguran tertulis pertama, Teguran tertulis kedua, Teguran tertulis ketiga (apabila pelanggaran disiplin)',
                'Fc. SK pemberhentian Sementara (apabila pelanggaran disiplin)',
                'Fc. Berita Acara Pemeriksaan (apabila pelanggaran disiplin)',
                'Fc. Berita Acara Hasil Rapat Tim Pemeriksa Pelanggaran Disiplin (apabila pelanggaran disiplin)',
                'Fc. Laporan Hasil Pemeriksaan dari Ketua TIM Pemeriksa Pelanggaran Disiplin (apabila pelanggaran disiplin)',
                'Fc. Surat Pemberitahuan Penjatuhan Disiplin dari Kepala Desa kepada Bupati lewat Camat (apabila pelanggaran disiplin)',
                'Fc. Surat Keputusan Penjatuhan Hukuman Disiplin dari Kepala Desa (apabila pelanggaran disiplin)',
                'Fc. SK penguatan Hukuman Disiplin dari Kepala Desa (apabila menolak keberatan yang diajukan Perangkat desa yang bersangkutan)',
                'Fc. Kartu Hukuman Disiplin (apabila pelanggaran disiplin)',
            ];

            $createTemplates = function ($items, $jenis_layanan_id, $alasan_id = null) {
                foreach ($items as $idx => $item) {
                    $wajib = (strpos($item, '(apabila') === false && strpos($item, 'apabila') === false);
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

            // Delete all draft ajuans so UI refreshes without errors from missing Template relationships
            Ajuan::query()->delete();
        });
    }
}
