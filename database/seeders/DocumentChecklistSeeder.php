<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisLayanan;
use App\Models\AlasanPemberhentian;
use App\Models\TemplateChecklist;

class DocumentChecklistSeeder extends Seeder
{
    public function run(): void
    {
        // Get or Create Jenis Layanan
        $pengangkatan = JenisLayanan::firstOrCreate(['nama' => 'Pengangkatan']);
        $rotasi = JenisLayanan::firstOrCreate(['nama' => 'Rotasi']);
        $pemberhentian = JenisLayanan::firstOrCreate(['nama' => 'Pemberhentian']);

        // Get or Create Alasan Pemberhentian
        $alasan = [
            'Purna Tugas', 'Mengundurkan Diri', 'Meninggal Dunia',
            'Berhalangan Tetap', 'Tindak Pidana', 'Pelanggaran Disiplin'
        ];
        
        $alasanModels = [];
        foreach ($alasan as $a) {
            $alasanModels[$a] = AlasanPemberhentian::firstOrCreate(['nama' => $a]);
        }

        // Clear existing template checklists to avoid duplicates (using delete to trigger cascade)
        TemplateChecklist::query()->delete();

        // 1. Dokumen Pengangkatan (23 items)
        $dokumenPengangkatan = [
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
            'Dokumentasi setiap tahapan penjaringan Perangkat Desa'
        ];

        foreach ($dokumenPengangkatan as $index => $dokumen) {
            TemplateChecklist::create([
                'jenis_layanan_id' => $pengangkatan->id,
                'nama_dokumen' => $dokumen,
                'urutan' => $index + 1,
                'wajib' => true,
            ]);
        }

        // 2. Dokumen Rotasi (13 items)
        $dokumenRotasi = [
            'Surat Pengantar dari Kecamatan',
            'Surat Usulan Rekomendasi Rotasi Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
            'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
            'Fc. Berita Acara Konsultasi Kepala Desa kepada Camat terkait akan dilaksanakannya rotasi Perangkat Desa',
            'Fc. Surat Pemberitahuan dari Kepala desa kepada BPD mengenai pelaksaaan rotasi',
            'Fc. Hasil Rincian perhitungan penilaian kinerja Perangkat desa yang akan dirotasi, apabila rotasi perangkat untuk mengisi jabatan Sekretaris Desa maka melampirkan rincian perhitungan penilaian kinerja seluruh jabatan Perangkat Desa ( contoh Kaur, Kasi dan Kadus)',
            'Fc. Berita Acara hasil penilaian kinerjaperangkat desa yang akan dirotasi',
            'Fc. Surat Permohonan Rekomendasi Rotasi Perangkat Desa dari Kepala Desa kepada Camat',
            'Fc. Rekomendasi Camat atas Proses Rotasi',
            'Fc. Sk Pengangkatan Pertama Perangkat Desa yang akan di Rotasi',
            'Fc. Ijasah atau Surat Tanda Tamat Belajar ( STTB )',
            'Fc. Daftar Hadir Perangkat Desa yang akan dirotasi selama 6 (enam) bulan sebelum pelaksaaan Rotasi',
            'Fc. Peraturan Kepala Desa tentang Tata Tertib Rotasi'
        ];

        foreach ($dokumenRotasi as $index => $dokumen) {
            TemplateChecklist::create([
                'jenis_layanan_id' => $rotasi->id,
                'nama_dokumen' => $dokumen,
                'urutan' => $index + 1,
                'wajib' => true,
            ]);
        }

        // 3. Dokumen Pemberhentian (purnatugas) (8 items)
        $dokumenPurnaTugas = [
            'Surat Pengantar dari Kecamatan',
            'Surat Usulan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
            'Fc. Surat Permohonan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Camat',
            'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
            'Fc. Rekomendasi Camat atas proses pemberhentian Perangkat Desa',
            'Fc. SK Pengangkatan Perangkat Desa',
            'Fc. Kartu Keluarga',
            'Fc. KTP atau Akta Kelahiran'
        ];

        foreach ($dokumenPurnaTugas as $index => $dokumen) {
            TemplateChecklist::create([
                'jenis_layanan_id' => $pemberhentian->id,
                'alasan_pemberhentian_id' => $alasanModels['Purna Tugas']->id,
                'nama_dokumen' => $dokumen,
                'urutan' => $index + 1,
                'wajib' => true,
            ]);
        }

        // 4. Dokumen Pemberhentian (permintaan sendiri) -> 'Mengundurkan Diri' (10 items)
        $dokumenPermintaanSendiri = [
            'Surat Pengantar dari Kecamatan',
            'Surat Usulan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
            'Fc. Surat Permohonan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Camat',
            'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
            'Fc. Rekomendasi Camat atas proses pemberhentian Perangkat Desa',
            'Fc. SK Pengangkatan Perangkat Desa',
            'Fc. Kartu Keluarga',
            'Fc. Surat Pernyataan Pengunduran diri dari Perangkat Desa yang ditujukan kepada Kepala Desa',
            'Fc. KTP atau Akta Kelahiran'
        ];

        foreach ($dokumenPermintaanSendiri as $index => $dokumen) {
            TemplateChecklist::create([
                'jenis_layanan_id' => $pemberhentian->id,
                'alasan_pemberhentian_id' => $alasanModels['Mengundurkan Diri']->id,
                'nama_dokumen' => $dokumen,
                'urutan' => $index + 1,
                'wajib' => true,
            ]);
        }

        // 5. Dokumen Pemberhentian (diberhentikan)
        // Common base items for all remaining reasons (1-7)
        $diberhentikanBase = [
            'Surat Pengantar dari Kecamatan',
            'Surat Usulan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Bupati lewat Camat',
            'Fc. Surat Permohonan Rekomendasi Pemberhentian Perangkat Desa dari Kepala Desa kepada Camat',
            'Surat Pernyataan kebenaran dokumen dari Kepala Desa (bermaterai)',
            'Fc. Rekomendasi Camat atas proses pemberhentian Perangkat Desa',
            'Fc. SK Pengangkatan Perangkat Desa',
            'Fc. Kartu Keluarga'
        ];

        $reasonsDiberhentikan = [
            'Meninggal Dunia' => [
                'Fc. Surat keterangan Kematian (apabila meninggal)'
            ],
            'Berhalangan Tetap' => [
                'Fc. Surat Keterangan dari Rumah Sakit yang menerangkan bahwa yang bersangkutan tidak dapat melaksanakan tugas dan kewajiban secara berturut-turut selama 6 (enam) bulan (apabila berhalangan tetap)'
            ],
            'Tindak Pidana' => [
                'Fc. Putusan dari pengadilan yang memiliki kekuatan hukum tetap (apabila melakukan Tindak Pidana)'
            ],
            'Pelanggaran Disiplin' => [
                'Fc. Bukti teguran lisan tercatat pertama, Teguran tertulis pertama, Teguran tertulis kedua, Teguran tertulis ketiga;',
                'Fc. SK pemberhentian Sementara;',
                'Fc. Berita Acara Pemeriksaan;',
                'Fc. Berita Acara Hasil Rapat Tim Pemeriksa Pelanggaran Disiplin;',
                'Fc. Laporan Hasil Pemeriksaan dari Ketua TIM Pemeriksa Pelanggaran Disiplin;',
                'Fc. Surat Pemberitahuan Penjatuhan Disiplin dari Kepala Desa kepada Bupati lewat Camat;',
                'Fc. Surat Keputusan Penjatuhan Hukuman Disiplin dari Kepala Desa',
                'Fc. SK penguatan Hukuman Disiplin dari Kepala Desa (apabila menolak keberatan yang diajukan Perangkat desa yang bersangkutan);',
                'Fc. Kartu Hukuman Disiplin'
            ]
        ];

        foreach ($reasonsDiberhentikan as $reasonName => $extraDocs) {
            $reasonId = $alasanModels[$reasonName]->id;
            $combinedDocs = array_merge($diberhentikanBase, $extraDocs);
            
            foreach ($combinedDocs as $index => $dokumen) {
                TemplateChecklist::create([
                    'jenis_layanan_id' => $pemberhentian->id,
                    'alasan_pemberhentian_id' => $reasonId,
                    'nama_dokumen' => $dokumen,
                    'urutan' => $index + 1,
                    'wajib' => true,
                ]);
            }
        }
    }
}
