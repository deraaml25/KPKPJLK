<?php

use App\Models\AlasanPemberhentian;
use App\Models\JenisLayanan;
use App\Models\TemplateChecklist;

$layananPj = JenisLayanan::firstOrCreate(['nama' => 'Pj Kades (Pemberhentian Definitif & Penunjukan Pj)']);
$layananPlt = JenisLayanan::firstOrCreate(['nama' => 'Plt Kades (Pemberhentian Sementara / Cuti & Penunjukan Plt)']);

TemplateChecklist::whereIn('jenis_layanan_id', [$layananPj->id, $layananPlt->id])->delete();

$alasanMeninggal = AlasanPemberhentian::firstOrCreate(['nama' => 'Meninggal Dunia']);
$alasanMundur = AlasanPemberhentian::firstOrCreate(['nama' => 'Permintaan Sendiri']);
$alasanDiberhentikan = AlasanPemberhentian::firstOrCreate(['nama' => 'Diberhentikan']); 

$pjKadesCommon = [
    'Surat Pengantar dari Kecamatan kepada Bupati',
    'Surat usulan Pj Kades dari Sekdes kepada Bupati lewat Camat',
    'Fc SK PNS (Calon Pj Kades) yang diusulkan',
    'Surat Pernyataan kesediaan menjadi Pj Kades bermaterai',
    'Fc Ijazah calon Pj Kades',
    'Fc KTP',
    'Fc KK',
    'Surat Pernyataan kebenaran dokumen dari Sekdes bermaterai',
    'Surat Pernyataan kebenaran dokumen dari calon Pj Kades',
    'Surat Keterangan pimpinan tempat bekerja calon Pj Kades terkait pencalonan ybs menjadi Pj Kades',
    'Permohonan rekom penunjukan kades di TTD Sekdes dan Ketua BPD kepada Camat',
    'Rekomendasi Camat tentang penunjukan Pj Kades',
    'Surat Pernyataan persetujuan penunjukan Pj Kades atas rekomendasi Camat di TTD Sekdes dan Ketua BPD',
    'Undangan, Daftar Hadir, BA, Dokumentasi Rapat Penunjukkan Pj Kades',
];

$meninggalItems = [
    'Surat permohonan pemberhentian Kades dari Kecamatan kepada Bupati c.q. Kadispermasdes',
    'Surat permohonan pemberhentian Kades dari BPD kepada Bupati lewat Camat',
    'SK Pengangkatan Kades induk dan penambahan',
    'Surat Kematian',
    'Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, Dokumentasi Rapat BPD tentang pemberhentian Kades',
];

$mundurItems = [
    'Surat permohonan pemberhentian Kades dari Kecamatan kepada Bupati c.q. Kadispermasdes',
    'Surat permohonan pemberhentian Kades dari BPD kepada Bupati lewat Camat',
    'SK Pengangkatan Kades induk dan penambahan',
    'Surat Pengunduran Diri bermaterai',
    'Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, Dokumentasi Rapat BPD tentang pemberhentian Kades',
];

$tidakHormatItems = [
    'Surat permohonan pemberhentian Kades dari Kecamatan kepada Bupati c.q. Kadispermasdes',
    'Surat permohonan pemberhentian Kades dari BPD kepada Bupati lewat Camat',
    'SK Pengangkatan Kades induk dan penambahan',
    'Surat Pelanggaran Disiplin / Dinyatakan sebagai terpidana berdasarkan putusan pengadilan yang telah mempunyai kekuatan hukum tetap',
    'Laporan BPD, Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, Dokumentasi Rapat BPD tentang pemberhentian Kades',
];

function insertChecklists($items, $layanan_id, $alasan_id) {
    foreach ($items as $idx => $item) {
        TemplateChecklist::create([
            'jenis_layanan_id' => $layanan_id,
            'alasan_pemberhentian_id' => $alasan_id,
            'nama_dokumen' => $item,
            'urutan' => $idx + 1,
            'wajib' => true,
        ]);
    }
}

insertChecklists(array_merge($meninggalItems, $pjKadesCommon), $layananPj->id, $alasanMeninggal->id);
insertChecklists(array_merge($mundurItems, $pjKadesCommon), $layananPj->id, $alasanMundur->id);
insertChecklists(array_merge($tidakHormatItems, $pjKadesCommon), $layananPj->id, $alasanDiberhentikan->id);

$alasanHukum = AlasanPemberhentian::firstOrCreate(['nama' => 'Pemberhentian Sementara (Hukum/Disiplin)']);
$alasanSakit = AlasanPemberhentian::firstOrCreate(['nama' => 'Cuti Sakit']);
$alasanUmroh = AlasanPemberhentian::firstOrCreate(['nama' => 'Cuti Umroh/Haji']);
$alasanTahunan = AlasanPemberhentian::firstOrCreate(['nama' => 'Cuti Tahunan']);
$alasanBersalin = AlasanPemberhentian::firstOrCreate(['nama' => 'Cuti Bersalin']);
$alasanPenting = AlasanPemberhentian::firstOrCreate(['nama' => 'Cuti Alasan Penting']);

$pltKadesCommon = [
    'Surat Pengantar dari Kecamatan kepada Bupati',
    'Surat Permohonan Usulan Pelaksana Tugas Kepala Desa dari Camat kepada Bupati Banyumas c.q. Kepala Dinpermasdes',
    'Surat Permohonan Usulan Pelaksana Tugas dari Kepala Desa kepada Bupati Banyumas melalui Camat',
    'SK Kades tentang pengangkatan Sekdes',
    'Fc KTP Sekdes',
    'Fc KK Sekdes',
];

$sementaraHukumItems = [
    'Surat Pengantar dari Kecamatan kepada Bupati',
    'Surat permohonan pemberhentian sementara Kades dari Kecamatan kepada Bupati c.q. Kadispermasdes',
    'Surat permohonan pemberhentian sementara Kades dari BPD kepada Bupati lewat Camat',
    'SK Pengangkatan Kades induk dan penambahan',
    'Bukti melanggar larangan/pelanggaran disiplin/penetapan terdakwa paling singkat 5 tahun, penetapan tersangka belum ada putusan pengadilan yang telah mempunyai kekuatan hukum tetap',
    'Fc KK',
    'Fc KTP',
    'Laporan BPD, Undangan Rapat/Musyawarah, Daftar Hadir, Berita Acara, Dokumentasi Rapat BPD tentang pemberhentian Kades',
];

// combine without the first common item because $sementaraHukumItems already has Pengantar
$hukumCommon = array_slice($pltKadesCommon, 1);
insertChecklists(array_merge($sementaraHukumItems, $hukumCommon), $layananPlt->id, $alasanHukum->id);

insertChecklists(array_merge(['Surat Dokter'], $pltKadesCommon), $layananPlt->id, $alasanSakit->id);
insertChecklists(array_merge(['Surat dari Biro Umroh/Haji'], $pltKadesCommon), $layananPlt->id, $alasanUmroh->id);
insertChecklists(array_merge(['Permohonan Cuti Tahunan'], $pltKadesCommon), $layananPlt->id, $alasanTahunan->id);
insertChecklists(array_merge(['Permohonan Cuti Bersalin'], $pltKadesCommon), $layananPlt->id, $alasanBersalin->id);
insertChecklists(array_merge(['Surat keterangan (misal keluarga meninggal pakai surat pernyataan atau surat kematian)'], $pltKadesCommon), $layananPlt->id, $alasanPenting->id);

echo "Template Checklist SK Kades (Pj Kades & Plt Kades) Berhasil di Generate!\n";
