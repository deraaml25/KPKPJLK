<?php

namespace Database\Seeders;

use App\Models\AlasanPemberhentian;
use App\Models\JenisLayanan;
use App\Models\TemplateChecklist;
use Illuminate\Database\Seeder;

class PjKadesChecklistSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jenis Layanan untuk SK Kades
        $layananPj = JenisLayanan::firstOrCreate(['nama' => 'Pj Kades (Pemberhentian Definitif & Penunjukan Pj)']);
        $layananPlt = JenisLayanan::firstOrCreate(['nama' => 'Plt Kades (Pemberhentian Sementara / Cuti & Penunjukan Plt)']);

        // 2. Alasan Pemberhentian / Cuti (We use these as the specific category the user selects)
        $alasanList = [
            // Definitif (Pj Kades)
            'Meninggal Dunia' => 'definitif',
            'Permintaan Sendiri' => 'definitif',
            'Diberhentikan' => 'definitif',
            'Pengangkatan Pj Kades' => 'definitif',

            // Sementara / Cuti (Plt Kades)
            'Pemberhentian Sementara' => 'sementara',
            'Pengangkatan Plt Kades' => 'sementara',
            'Cuti Sakit' => 'sementara',
            'Cuti Tahunan' => 'sementara',
            'Cuti Bersalin' => 'sementara',
            'Cuti Alasan Penting' => 'sementara',
        ];

        $alasanModels = [];
        foreach ($alasanList as $namaAlasan => $tipe) {
            $alasanModels[$namaAlasan] = AlasanPemberhentian::firstOrCreate(['nama' => $namaAlasan]);
        }

        // ==========================================
        // DOKUMEN PENGUSULAN SK PEMBERHENTIAN KADES
        // ==========================================

        // --- 1. Karena Meninggal Dunia ---
        $docsMeninggal = [
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Kades dari Kecamatan kepada Bupati c.q. Kepala Dinpermasdes',
            'Surat Permohonan Pemberhentian Kades dari BPD kepada Bupati melalui Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Surat Kematian',
            'Fotokopi KK Kades',
            'Fotokopi KTP Kades',
            'Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, dan Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Meninggal Dunia']->id, $docsMeninggal);

        // --- 2. Karena Permintaan Sendiri ---
        $docsPermintaanSendiri = [
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Kades dari Kecamatan kepada Bupati c.q. Kepala Dinpermasdes',
            'Surat Permohonan Pemberhentian Kades dari BPD kepada Bupati melalui Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Surat Pengunduran Diri Bermeterai',
            'Fotokopi KK Kades',
            'Fotokopi KTP Kades',
            'Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, dan Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Permintaan Sendiri']->id, $docsPermintaanSendiri);

        // --- 3. Karena Diberhentikan Dengan Tidak Hormat ---
        $docsDiberhentikan = [
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Kades dari Kecamatan kepada Bupati c.q. Kepala Dinpermasdes',
            'Surat Permohonan Pemberhentian Kades dari BPD kepada Bupati melalui Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Surat Pelanggaran Disiplin/Dinyatakan sebagai Terpidana berdasarkan Putusan Pengadilan yang telah Berkekuatan Hukum Tetap',
            'Fotokopi KK Kades',
            'Fotokopi KTP Kades',
            'Laporan BPD, Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, dan Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Diberhentikan']->id, $docsDiberhentikan);

        // --- 4. Pengangkatan Pj Kades ---
        $docsPengangkatanPj = [
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Usulan Pj Kades dari Sekdes kepada Bupati melalui Camat',
            'Fotokopi SK PNS (Calon Pj Kades) yang Diusulkan',
            'Surat Pernyataan Kesediaan Menjadi Pj Kades Bermeterai',
            'Fotokopi Ijazah Calon Pj Kades',
            'Fotokopi KTP Calon Pj Kades',
            'Fotokopi KK Calon Pj Kades',
            'Surat Pernyataan Kebenaran Dokumen dari Sekdes Bermeterai',
            'Surat Pernyataan Kebenaran Dokumen dari Calon Pj Kades Bermeterai',
            'Surat Keterangan Pimpinan Tempat Bekerja Calon Pj Kades terkait Pencalonan Ybs Menjadi Pj Kades',
            'Permohonan Rekomendasi Penunjukan Pj Kades yang Ditandatangani Sekdes dan Ketua BPD kepada Camat',
            'Rekomendasi Camat tentang Penunjukan Pj Kades',
            'Surat Pernyataan Persetujuan Penunjukan Pj Kades atas Rekomendasi Camat yang Ditandatangani Sekdes dan Ketua BPD',
            'Undangan, Daftar Hadir, Berita Acara, dan Dokumentasi Rapat Penunjukan Pj Kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Pengangkatan Pj Kades']->id, $docsPengangkatanPj);

        // ==========================================
        // DOKUMEN PENGUSULAN SK PEMBERHENTIAN KADES SEMENTARA & PLT
        // ==========================================

        // --- 5. Pemberhentian Kades Sementara ---
        $docsSementara = [
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Sementara Kades dari Kecamatan kepada Bupati c.q. Kepala Dinpermasdes',
            'Surat Permohonan Pemberhentian Sementara Kades dari BPD kepada Bupati melalui Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Bukti Melanggar Larangan/Pelanggaran Disiplin/Penetapan Terdakwa Paling Singkat 5 Tahun/Penetapan Tersangka Belum Ada Putusan Pengadilan yang telah Berkekuatan Hukum Tetap',
            'Fotokopi KK Kades',
            'Fotokopi KTP Kades',
            'Laporan BPD, Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, dan Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ];
        $this->createTemplates($layananPlt->id, $alasanModels['Pemberhentian Sementara']->id, $docsSementara);

        // --- 6. Pengangkatan Plt Kades ---
        $docsPengangkatanPlt = [
            'Surat Pengantar dari Kecamatan',
            'Surat Permohonan Usulan Pelaksana Tugas Kepala Desa dari Camat kepada Bupati Banyumas c.q. Kepala Dinpermasdes',
            'Surat Permohonan Usulan Pelaksana Tugas dari Kepala Desa kepada Bupati Banyumas melalui Camat',
            'SK Kades tentang Pengangkatan Sekdes',
            'Fotokopi KTP Sekdes',
            'Fotokopi KK Sekdes',
        ];
        $this->createTemplates($layananPlt->id, $alasanModels['Pengangkatan Plt Kades']->id, $docsPengangkatanPlt);

        // --- 7. Cuti Kades (Tambahan opsional jika dibutuhkan) ---
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Sakit']->id, ['Surat Keterangan Dokter']);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Tahunan']->id, ['Permohonan Cuti Tahunan']);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Bersalin']->id, ['Permohonan Cuti Bersalin']);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Alasan Penting']->id, ['Surat keterangan yang menyertakan alasan (misal: surat pernyataan/surat kematian/surat dari biro)']);
    }

    private function createTemplates(int $jenisLayananId, int $alasanId, array $documents): void
    {
        // Remove old entries for this combination
        TemplateChecklist::where('jenis_layanan_id', $jenisLayananId)
            ->where('alasan_pemberhentian_id', $alasanId)
            ->delete();

        foreach ($documents as $index => $namaDokumen) {
            TemplateChecklist::create([
                'jenis_layanan_id' => $jenisLayananId,
                'alasan_pemberhentian_id' => $alasanId,
                'nama_dokumen' => ucfirst($namaDokumen),
                'urutan' => $index + 1,
                'wajib' => true,
            ]);
        }
    }
}
