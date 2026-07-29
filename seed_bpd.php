<?php

$mengundurkanDiri = App\Models\AlasanPemberhentian::where('nama', 'Mengundurkan Diri')->first()->id;
$meninggalDunia = App\Models\AlasanPemberhentian::where('nama', 'Meninggal Dunia')->first()->id;
$diberhentikan = App\Models\AlasanPemberhentian::where('nama', 'Diberhentikan')->first()->id;

Schema::disableForeignKeyConstraints();
App\Models\TemplateChecklistBpd::truncate();
App\Models\ChecklistAjuanBpd::truncate();
Schema::enableForeignKeyConstraints();

// Mengundurkan Diri
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'pemberhentian', 'alasan_pemberhentian_id' => $mengundurkanDiri, 'nama_dokumen' => 'Surat Pengantar Kepala Desa', 'wajib' => true, 'urutan' => 1]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'pemberhentian', 'alasan_pemberhentian_id' => $mengundurkanDiri, 'nama_dokumen' => 'Surat Pengunduran Diri', 'wajib' => true, 'urutan' => 2]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'pemberhentian', 'alasan_pemberhentian_id' => $mengundurkanDiri, 'nama_dokumen' => 'Fotokopi KTP', 'wajib' => true, 'urutan' => 3]);

// Meninggal Dunia
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'pemberhentian', 'alasan_pemberhentian_id' => $meninggalDunia, 'nama_dokumen' => 'Surat Pengantar Kepala Desa', 'wajib' => true, 'urutan' => 1]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'pemberhentian', 'alasan_pemberhentian_id' => $meninggalDunia, 'nama_dokumen' => 'Surat Keterangan Kematian', 'wajib' => true, 'urutan' => 2]);

// Diberhentikan
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'pemberhentian', 'alasan_pemberhentian_id' => $diberhentikan, 'nama_dokumen' => 'Surat Pengantar Kepala Desa', 'wajib' => true, 'urutan' => 1]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'pemberhentian', 'alasan_pemberhentian_id' => $diberhentikan, 'nama_dokumen' => 'Dokumen Bukti Pelanggaran / Putusan Pengadilan', 'wajib' => true, 'urutan' => 2]);

// Peresmian
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'peresmian', 'alasan_pemberhentian_id' => null, 'nama_dokumen' => 'Surat Pengantar Kepala Desa', 'wajib' => true, 'urutan' => 1]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'peresmian', 'alasan_pemberhentian_id' => null, 'nama_dokumen' => 'Berita Acara Musyawarah Desa', 'wajib' => true, 'urutan' => 2]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'peresmian', 'alasan_pemberhentian_id' => null, 'nama_dokumen' => 'Daftar Hadir Musyawarah Desa', 'wajib' => true, 'urutan' => 3]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'peresmian', 'alasan_pemberhentian_id' => null, 'nama_dokumen' => 'Fotokopi Ijazah', 'wajib' => true, 'urutan' => 4]);
App\Models\TemplateChecklistBpd::create(['jenis_ajuan' => 'peresmian', 'alasan_pemberhentian_id' => null, 'nama_dokumen' => 'Fotokopi KTP', 'wajib' => true, 'urutan' => 5]);

echo "Seeded TemplateChecklistBpd successfully.\n";
