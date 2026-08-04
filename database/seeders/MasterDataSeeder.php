<?php

namespace Database\Seeders;

use App\Models\AlasanPemberhentian;
use App\Models\Desa;
use App\Models\JenisLayanan;
use App\Models\Kecamatan;
use App\Models\PerangkatDesa;
use App\Models\TemplateChecklist;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Jenis Layanan
        $pengangkatan = JenisLayanan::create(['nama' => 'Pengangkatan']);
        $rotasi = JenisLayanan::create(['nama' => 'Rotasi']);
        $pemberhentian = JenisLayanan::create(['nama' => 'Pemberhentian']);

        // 2. Alasan Pemberhentian (Termasuk untuk Kades & Perangkat Desa)
        $alasan = [
            'Purna Tugas', 'Mengundurkan Diri', 'Meninggal Dunia',
            'Berhalangan Tetap', 'Tindak Pidana', 'Pelanggaran Disiplin',
            'Permintaan Sendiri', 'Diberhentikan Dengan Tidak Hormat',
            'Pemberhentian Sementara', 'Cuti Sakit', 'Cuti Umroh / Haji',
            'Cuti Tahunan', 'Cuti Bersalin', 'Cuti Alasan Penting',
        ];
        foreach ($alasan as $a) {
            AlasanPemberhentian::firstOrCreate(['nama' => $a]);
        }

        // 3. Dummy Data (Kecamatan, Desa, User, Perangkat)
        $kec = Kecamatan::create(['nama_kecamatan' => 'Sumbang']);
        $desa = Desa::create(['nama_desa' => 'Karangendep', 'kecamatan_id' => $kec->id]);

        User::create([
            'name' => 'Operator Karangendep',
            'username' => 'karangendep',
            'password' => bcrypt('password'),
            'role' => 'desa',
            'desa_id' => $desa->id,
        ]);

        User::create([
            'name' => 'Admin Dinpermasdes',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        PerangkatDesa::create([
            'desa_id' => $desa->id,
            'nama' => 'Budi Santoso',
            'jabatan' => 'Kaur Keuangan',
        ]);

        // 4. Dummy Data Template Checklist
        $templates = [
            ['nama' => 'Surat Pengantar Kepala Desa', 'wajib' => true],
            ['nama' => 'Salinan Keputusan Kepala Desa tentang Pembentukan Panitia', 'wajib' => true],
            ['nama' => 'Berita Acara Rapat Panitia', 'wajib' => true],
            ['nama' => 'Fotokopi KTP Calon Perangkat Desa', 'wajib' => true],
            ['nama' => 'Fotokopi Ijazah (Dilegalisir)', 'wajib' => true],
            ['nama' => 'Surat Keterangan Sehat dari Puskesmas/RSUD', 'wajib' => true],
        ];

        $urutan = 1;
        foreach ($templates as $tmpl) {
            TemplateChecklist::create([
                'jenis_layanan_id' => $pengangkatan->id,
                'nama_dokumen' => $tmpl['nama'],
                'urutan' => $urutan++,
                'wajib' => $tmpl['wajib'],
            ]);
        }

        $urutan = 1;
        TemplateChecklist::create([
            'jenis_layanan_id' => $pemberhentian->id,
            'alasan_pemberhentian_id' => AlasanPemberhentian::where('nama', 'Purna Tugas')->first()->id,
            'nama_dokumen' => 'Surat Pengantar Kepala Desa',
            'urutan' => $urutan++,
            'wajib' => true,
        ]);
        TemplateChecklist::create([
            'jenis_layanan_id' => $pemberhentian->id,
            'alasan_pemberhentian_id' => AlasanPemberhentian::where('nama', 'Purna Tugas')->first()->id,
            'nama_dokumen' => 'Fotokopi SK Pengangkatan',
            'urutan' => $urutan++,
            'wajib' => true,
        ]);
    }
}
