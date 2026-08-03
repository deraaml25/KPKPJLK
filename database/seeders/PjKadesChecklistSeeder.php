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
            'Pemberhentian Kades karena Meninggal Dunia' => 'definitif',
            'Pemberhentian Kades karena Permintaan Sendiri' => 'definitif',
            'Pemberhentian Kades karena Diberhentikan dengan Tidak Hormat' => 'definitif',
            'Pengangkatan Pj Kades' => 'definitif',

            // Sementara / Cuti (Plt Kades)
            'Pemberhentian Kades Sementara' => 'sementara',
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
            'Surat pengantar dr kec kepada bupati',
            'Surat permohonan pemberhentian kades dari kecamatan, kpd bupati cq kadispermasdes',
            'Surat permohonan pemberhentian kades dari bpd kpd bupati lwt camat',
            'SK pengangkatan kades induk dan penambahan',
            'Surat kematian',
            'Fc kk kades',
            'Fc ktp kades',
            'Undangan rapat/musyawarah, daftarhadir, Berita acara, dokumentasi rapat bpd ttg pemberhentian kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Pemberhentian Kades karena Meninggal Dunia']->id, $docsMeninggal);

        // --- 2. Karena Permintaan Sendiri ---
        $docsPermintaanSendiri = [
            'Surat pengantar dr kec kepada bupati',
            'Surat permohonan pemberhentian kades dari kecamatan, kpd bupati cq kadispermasdes',
            'Surat permohonan pemberhentian kades dari bpd kpd bupati lwt camat',
            'SK pengangkatan kades induk dan penambahan',
            'Surat pengunduran diri bermaterai',
            'Fc kk kades',
            'Fc ktp kades',
            'Undangan rapat/musyawarah, daftarhadir, Berita acara, dokumentasi rapat bpd ttg pemberhentian kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Pemberhentian Kades karena Permintaan Sendiri']->id, $docsPermintaanSendiri);

        // --- 3. Karena Diberhentikan Dengan Tidak Hormat ---
        $docsDiberhentikan = [
            'Surat pengantar dr kec kepada bupati',
            'Surat permohonan pemberhentian kades dari kecamatan, kpd bupati cq kadispermasdes',
            'Surat permohonan pemberhentian kades dari bpd kpd bupati lwt camat',
            'SK pengangkatan kades induk dan penambahan',
            'Surat pelanggaran disiplin/Dinyatakan sebagai terpidana berdasarkan putusan pengadilan yang telah mempunyai kekuatan hukum tetap',
            'Fc kk kades',
            'Fc ktp kades',
            'Laporan bpd, undangan rapat/musyawarah, daftarhadir, Berita acara, dokumentasi rapat bpd ttg pemberhentian kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Pemberhentian Kades karena Diberhentikan dengan Tidak Hormat']->id, $docsDiberhentikan);

        // --- 4. Pengangkatan Pj Kades ---
        $docsPengangkatanPj = [
            'Surat pengantar dr kec kpd bupati',
            'Surat usulan pj kades dari sekdes kpd bupati lewat camat',
            'Fc sk pns (calon pj kades) yg diusulkan',
            'Surat pernyataan kesediaan menjadi pj kades bermaterai',
            'Fc ijazah calon pj kades',
            'Fc ktp calon pj kades',
            'Fc kk calon pj kades',
            'Surat pernyataan kebenaran dokumen dari sekdes bermaterai',
            'Surat pernyataan kebenaran dokumen dari calon pj kades',
            'Surat keterangan pimpinan tempat bekerja calon pj kades terkait pencalonan ybs menjadi pj kades',
            'Permohonan rekom penunjukan kades di ttd sekdes dan ketua bpd kpd camat',
            'Rekomendasi camat ttg penunjukan pj kades',
            'Surat pernyataan persetujuan penunjukan pj kades atas rekomendasi camat di ttd sekdes dan ketua bpd',
            'Undangan daftar hadir BA dokumentasi rapat penunjukkan pj kades',
        ];
        $this->createTemplates($layananPj->id, $alasanModels['Pengangkatan Pj Kades']->id, $docsPengangkatanPj);

        // ==========================================
        // DOKUMEN PENGUSULAN SK PEMBERHENTIAN KADES SEMENTARA & PLT
        // ==========================================
        
        // --- 5. Pemberhentian Kades Sementara ---
        $docsSementara = [
            'Surat pengantar dr kec kepada bupati',
            'Surat permohonan pemberhentian sementara kades dari kecamatan, kpd bupati cq kadispermasdes',
            'Surat permohonan pemberhentian sementara kades dari bpd kpd bupati lwt camat',
            'SK pengangkatan kades induk dan penambahan',
            'Bukti melanggar larangan/pelanggaran disiplin/penetapan terdakwa paling singkat 5 tahun, penetapan tersangka belum ada putusan pengadilan yang telah mempunyai kekuatan hukum tetap',
            'Fc kk kades',
            'Fc ktp kades',
            'Laporan bpd, undangan rapat/musyawarah, daftarhadir, Berita acara, dokumentasi rapat bpd ttg pemberhentian kades',
        ];
        $this->createTemplates($layananPlt->id, $alasanModels['Pemberhentian Kades Sementara']->id, $docsSementara);

        // --- 6. Pengangkatan Plt Kades ---
        $docsPengangkatanPlt = [
            'Surat pengantar dari kecamatan',
            'Surat permohonan usulan pelaksana tugas kepala desa dari camat kepada bupati banyumas c.q. kepala dinpermasdes',
            'Surat permohonan usulan pelaksana tugas dari kepala desa kepada bupati banyumas melalui camat',
            'SK kades tentang pengangkatan sekdes',
            'Fc ktp sekdes',
            'Fc kk sekdes',
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

