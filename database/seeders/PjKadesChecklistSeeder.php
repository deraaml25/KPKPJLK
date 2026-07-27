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

        // 2. Alasan Pemberhentian / Cuti
        $alasanList = [
            // Definitif (Pj Kades)
            'Meninggal Dunia' => 'definitif',
            'Permintaan Sendiri' => 'definitif',
            'Diberhentikan Dengan Tidak Hormat' => 'definitif',

            // Sementara / Cuti (Plt Kades)
            'Pemberhentian Sementara' => 'sementara',
            'Cuti Sakit' => 'sementara',
            'Cuti Umroh / Haji' => 'sementara',
            'Cuti Tahunan' => 'sementara',
            'Cuti Bersalin' => 'sementara',
            'Cuti Alasan Penting' => 'sementara',
        ];

        $alasanModels = [];
        foreach ($alasanList as $namaAlasan => $tipe) {
            $alasanModels[$namaAlasan] = AlasanPemberhentian::firstOrCreate(['nama' => $namaAlasan]);
        }

        // ==========================================
        // DOKUMEN PERSYARATAN PJ KADES (PNS) - COMMON (14 items)
        // ==========================================
        $persyaratanPjKadesPns = [
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Usulan Pj Kepala Desa dari Sekdes kepada Bupati lewat Camat',
            'Fc. SK PNS Calon Pj Kepala Desa yang diusulkan',
            'Surat Pernyataan Kesediaan Menjadi Pj Kepala Desa (bermaterai)',
            'Fc. Ijazah Calon Pj Kepala Desa',
            'Fc. KTP Calon Pj Kepala Desa',
            'Fc. KK Calon Pj Kepala Desa',
            'Surat Pernyataan Kebenaran Dokumen dari Sekdes (bermaterai)',
            'Surat Pernyataan Kebenaran Dokumen dari Calon Pj Kepala Desa',
            'Surat Keterangan Pimpinan Tempat Bekerja Calon Pj Kepala Desa terkait pencalonan ybs',
            'Permohonan Rekomendasi Penunjukan Pj Kepala Desa (ttd Sekdes & Ketua BPD kepada Camat)',
            'Rekomendasi Camat tentang Penunjukan Pj Kepala Desa',
            'Surat Pernyataan Persetujuan Penunjukan Pj Kepala Desa atas Rekomendasi Camat (ttd Sekdes & Ketua BPD)',
            'Undangan, Daftar Hadir, Berita Acara, & Dokumentasi Rapat Penunjukan Pj Kepala Desa',
        ];

        // --- GROUP A: PJ KADES (Meninggal Dunia) ---
        $docsPjMeninggal = array_merge([
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Kades dari Kecamatan kepada Bupati cq. Kadispermasdes',
            'Surat Permohonan Pemberhentian Kades dari BPD kepada Bupati lewat Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Surat Kematian',
            'Fc. KK Kades',
            'Fc. KTP Kades',
            'Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, & Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ], $persyaratanPjKadesPns);

        $this->createTemplates($layananPj->id, $alasanModels['Meninggal Dunia']->id, $docsPjMeninggal);

        // --- GROUP A: PJ KADES (Permintaan Sendiri) ---
        $docsPjPermintaanSendiri = array_merge([
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Kades dari Kecamatan kepada Bupati cq. Kadispermasdes',
            'Surat Permohonan Pemberhentian Kades dari BPD kepada Bupati lewat Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Surat Pengunduran Diri (bermaterai)',
            'Fc. KK Kades',
            'Fc. KTP Kades',
            'Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, & Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ], $persyaratanPjKadesPns);

        $this->createTemplates($layananPj->id, $alasanModels['Permintaan Sendiri']->id, $docsPjPermintaanSendiri);

        // --- GROUP A: PJ KADES (Diberhentikan Dengan Tidak Hormat) ---
        $docsPjDiberhentikan = array_merge([
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Kades dari Kecamatan kepada Bupati cq. Kadispermasdes',
            'Surat Permohonan Pemberhentian Kades dari BPD kepada Bupati lewat Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Surat Pelanggaran Disiplin / Putusan Pengadilan kekuatan hukum tetap (Terpidana)',
            'Fc. KK Kades',
            'Fc. KTP Kades',
            'Laporan BPD, Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, & Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ], $persyaratanPjKadesPns);

        $this->createTemplates($layananPj->id, $alasanModels['Diberhentikan Dengan Tidak Hormat']->id, $docsPjDiberhentikan);

        // ==========================================
        // DOKUMEN PENDUKUNG PLT KADES (SEKDES) - COMMON (6 items)
        // ==========================================
        $pendukungPltSekdes = [
            'Surat Pengantar dari Kecamatan',
            'Surat Permohonan Usulan Plt Kepala Desa dari Camat kepada Bupati Banyumas cq. Kepala Dinpermasdes',
            'Surat Permohonan Usulan Plt Kepala Desa dari Kepala Desa kepada Bupati Banyumas melalui Camat',
            'SK Kades tentang Pengangkatan Sekdes',
            'Fc. KTP Sekdes',
            'Fc. KK Sekdes',
        ];

        // --- GROUP B: PLT KADES (Pemberhentian Sementara) ---
        $docsPltSementara = array_merge([
            'Surat Pengantar dari Kecamatan kepada Bupati',
            'Surat Permohonan Pemberhentian Sementara Kades dari Kecamatan kepada Bupati cq. Kadispermasdes',
            'Surat Permohonan Pemberhentian Sementara Kades dari BPD kepada Bupati lewat Camat',
            'SK Pengangkatan Kades Induk dan Penambahan',
            'Bukti Melanggar Larangan / Pelanggaran Disiplin / Penetapan Terdakwa (min 5 thn) / Penetapan Tersangka belum Inkracht',
            'Fc. KK Kades',
            'Fc. KTP Kades',
            'Laporan BPD, Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, & Dokumentasi Rapat BPD tentang Pemberhentian Kades',
        ], $pendukungPltSekdes);

        $this->createTemplates($layananPlt->id, $alasanModels['Pemberhentian Sementara']->id, $docsPltSementara);

        // --- GROUP B: PLT KADES (Cuti Sakit) ---
        $docsPltSakit = array_merge([
            'Surat Keterangan Dokter / Rumah Sakit',
        ], $pendukungPltSekdes);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Sakit']->id, $docsPltSakit);

        // --- GROUP B: PLT KADES (Cuti Umroh / Haji) ---
        $docsPltHaji = array_merge([
            'Surat Keterangan / Konfirmasi dari Biro Perjalanan Umroh / Haji',
        ], $pendukungPltSekdes);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Umroh / Haji']->id, $docsPltHaji);

        // --- GROUP B: PLT KADES (Cuti Tahunan) ---
        $docsPltTahunan = array_merge([
            'Surat Permohonan Cuti Tahunan Kepala Desa',
        ], $pendukungPltSekdes);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Tahunan']->id, $docsPltTahunan);

        // --- GROUP B: PLT KADES (Cuti Bersalin) ---
        $docsPltBersalin = array_merge([
            'Surat Permohonan Cuti Bersalin / Surat Keterangan Dokter Bidan',
        ], $pendukungPltSekdes);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Bersalin']->id, $docsPltBersalin);

        // --- GROUP B: PLT KADES (Cuti Alasan Penting) ---
        $docsPltPenting = array_merge([
            'Surat Keterangan / Surat Pernyataan Alasan Penting / Surat Kematian',
        ], $pendukungPltSekdes);
        $this->createTemplates($layananPlt->id, $alasanModels['Cuti Alasan Penting']->id, $docsPltPenting);
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
                'nama_dokumen' => $namaDokumen,
                'urutan' => $index + 1,
                'wajib' => true,
            ]);
        }
    }
}
