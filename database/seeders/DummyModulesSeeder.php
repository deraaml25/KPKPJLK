<?php

namespace Database\Seeders;

use App\Models\Bimtek;
use App\Models\BimtekPendaftaran;
use App\Models\Desa;
use App\Models\IzinCalon;
use App\Models\PenataanDesa;
use App\Models\PerangkatDesa;
use App\Models\Pilkades;
use App\Models\PjKades;
use App\Models\Regulasi;
use App\Models\Siltap;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyModulesSeeder extends Seeder
{
    public function run(): void
    {
        $desa = Desa::first();
        if (! $desa) {
            return;
        }

        // 1. e-Regulasi
        Regulasi::create([
            'no_regulasi' => 'PRD/2026/07/0001',
            'judul' => 'Peraturan Desa Karangendep tentang Rencana Pembangunan Jangka Menengah Desa (RPJMDes) Tahun 2026-2032',
            'deskripsi' => 'Draf Usulan RPJMDes hasil Musrenbangdes untuk penyelarasan dinas.',
            'tipe' => 'perdes',
            'file_path' => 'regulasi/draft/dummy.docx',
            'status' => 'diajukan',
            'desa_id' => $desa->id,
            'tgl_diajukan' => now()->subDays(5),
        ]);

        Regulasi::create([
            'no_regulasi' => 'PKD/2026/07/0002',
            'judul' => 'Peraturan Kepala Desa Karangendep tentang Pembagian Rincian BLT Dana Desa',
            'deskripsi' => 'Penyesuaian Lampiran sasaran penerima manfaat KPM.',
            'tipe' => 'perkades',
            'file_path' => 'regulasi/draft/dummy2.docx',
            'status' => 'disahkan',
            'desa_id' => $desa->id,
            'tgl_diajukan' => now()->subDays(10),
            'tgl_disahkan' => now()->subDays(8),
            'catatan_revisi' => 'Pasal 3 ayat 2 diselaraskan dengan instruksi Bupati.',
        ]);

        // 2. e-Bimtek
        $bimtek1 = Bimtek::create([
            'judul' => 'Pelatihan Pengelolaan Siskeudes dan Sipades Versi 2026',
            'deskripsi' => 'Bimtek wajib bagi Sekretaris Desa dan Kaur Keuangan perihal penatausahaan aset keuangan desa.',
            'tanggal_pelaksanaan' => now()->addDays(7),
            'kuota' => 50,
            'tempat' => 'Aula Dinpermasdes Kabupaten / Zoom',
        ]);

        $bimtek2 = Bimtek::create([
            'judul' => 'Sertifikasi Legal Drafting Produk Hukum Desa bagi BPD',
            'deskripsi' => 'Peningkatan kapasitas perancangan peraturan desa terpadu.',
            'tanggal_pelaksanaan' => now()->addDays(15),
            'kuota' => 30,
            'tempat' => 'Lantai 2 Gedung Diklat Daerah',
        ]);

        $user = User::where('desa_id', $desa->id)->first();

        BimtekPendaftaran::create([
            'bimtek_id' => $bimtek1->id,
            'user_id' => $user->id,
            'desa_id' => $desa->id,
            'perangkat_desa_id' => PerangkatDesa::where('desa_id', $desa->id)->first()->id,
            'status_presensi' => 'absen',
            'file_rtl' => null,
        ]);

        // 3. e-Siltap
        Siltap::create([
            'desa_id' => $desa->id,
            'bulan' => 6,
            'tahun' => 2026,
            'rekomendasi_camat_path' => 'siltap/rekomindasi_camat_dummy.pdf',
            'bukti_bpjs_path' => 'siltap/bukti_bpjs_dummy.pdf',
            'spj_path' => 'siltap/spj_dummy.pdf',
            'status' => 'approved',
            'sp2d_path' => 'siltap/sp2d_dummy.pdf',
            'catatan_verifikator' => 'Telah diproses dan dana telah dikirim ke Bank Jateng cabang terdekat.',
        ]);

        Siltap::create([
            'desa_id' => $desa->id,
            'bulan' => 7,
            'tahun' => 2026,
            'rekomendasi_camat_path' => 'siltap/rekomindasi_camat_dummy2.pdf',
            'bukti_bpjs_path' => 'siltap/bukti_bpjs_dummy2.pdf',
            'spj_path' => 'siltap/spj_dummy2.pdf',
            'status' => 'pending',
            'sp2d_path' => null,
            'catatan_verifikator' => null,
        ]);

        // 4. e-PjKades
        PjKades::create([
            'desa_id' => $desa->id,
            'nama_pns' => 'Drs. Siswanto, M.Si',
            'nip' => '197510122002121003',
            'pangkat' => 'Penata Tk. I / IV a',
            'riwayat_hidup_path' => 'pjkades/cv_dummy.pdf',
            'sk_pangkat_path' => 'pjkades/sk_pangkat_dummy.pdf',
            'status_bebas_hukdis' => 'clean',
            'status' => 'pending',
            'sk_bupati_path' => null,
        ]);

        // 5. e-IzinCalon
        IzinCalon::create([
            'desa_id' => $desa->id,
            'nama_calon' => 'Rahmat Hidayat',
            'jabatan_sekarang' => 'Kepala Urusan Pemerintahan (Kaur)',
            'jenis_calon' => 'perangkat',
            'bebas_temuan_inspektorat_path' => 'izincalon/inspektorat_dummy.pdf',
            'berkas_syarat_path' => 'izincalon/syarat_dummy.pdf',
            'status' => 'pending',
            'catatan_inspektorat' => null,
        ]);

        // 6. e-Pilkades
        $pil = Pilkades::create([
            'desa_id' => $desa->id,
            'tanggal_pemungutan' => now()->subDays(2),
            'total_tps' => 3,
            'status' => 'pemilihan',
            'pemenang_nama' => null,
            'sk_bupati_path' => null,
        ]);

        $pil->suaras()->create([
            'tps_name' => 'TPS 01 - Balai RW 01',
            'suara_calon_1' => 120,
            'suara_calon_2' => 85,
            'suara_calon_3' => 95,
        ]);

        $pil->suaras()->create([
            'tps_name' => 'TPS 02 - SD Negeri 1',
            'suara_calon_1' => 140,
            'suara_calon_2' => 110,
            'suara_calon_3' => 105,
        ]);

        // 7. e-PenataanDesa
        PenataanDesa::create([
            'desa_id' => $desa->id,
            'tipe' => 'pemekaran',
            'nama_wilayah_baru' => 'Dusun Karanganyar Selatan',
            'jumlah_penduduk' => 6100,
            'jumlah_kk' => 1250,
            'proposal_path' => 'penataan/proposal_dummy.pdf',
            'status' => 'pending',
            'rekomendasi_dinas_path' => null,
        ]);
    }
}
