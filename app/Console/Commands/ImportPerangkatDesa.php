<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\PerangkatDesa;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ImportPerangkatDesa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-perangkat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data perangkat desa dari file data_desa.json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonFile = base_path('data_desa.json');

        if (!File::exists($jsonFile)) {
            $this->error("File $jsonFile tidak ditemukan!");
            return;
        }

        $jsonData = json_decode(File::get($jsonFile), true);

        if (!$jsonData) {
            $this->error("Gagal membaca atau mem-parse JSON.");
            return;
        }

        // Row 0 is header, so shift it
        array_shift($jsonData);

        $insertedCount = 0;

        DB::transaction(function () use ($jsonData, &$insertedCount) {
            foreach ($jsonData as $index => $row) {
                $kecamatanName = trim($row['B'] ?? '');
                $desaName = trim($row['C'] ?? '');

                if (empty($kecamatanName) || empty($desaName)) {
                    $this->warn("Baris " . ($index + 2) . " diskip karena Kecamatan atau Desa kosong.");
                    continue;
                }

                // Get or create Kecamatan
                $kecamatan = Kecamatan::firstOrCreate(['nama_kecamatan' => $kecamatanName]);

                // Get or create Desa
                $desa = Desa::firstOrCreate([
                    'nama_desa' => $desaName,
                    'kecamatan_id' => $kecamatan->id
                ]);

                // Data mapping for Jabatan & Nama (Column letter)
                $perangkatList = [
                    ['jabatan' => 'Kepala Desa', 'nama' => trim($row['D'] ?? '')],
                    ['jabatan' => 'Sekretaris Desa', 'nama' => trim($row['E'] ?? '')],
                    ['jabatan' => 'Kasi Pemerintahan', 'nama' => trim($row['F'] ?? '')],
                    ['jabatan' => 'Kasi Kesejahteraan', 'nama' => trim($row['G'] ?? '')],
                    ['jabatan' => 'Kasi Pelayanan', 'nama' => trim($row['H'] ?? '')],
                    ['jabatan' => 'Kaur Keuangan', 'nama' => trim($row['I'] ?? '')],
                    ['jabatan' => 'Kaur Perencanaan', 'nama' => trim($row['J'] ?? '')],
                    ['jabatan' => 'Kaur TU & Umum', 'nama' => trim($row['K'] ?? '')],
                    ['jabatan' => 'Kadus I', 'nama' => trim($row['L'] ?? '')],
                    ['jabatan' => 'Kadus II', 'nama' => trim($row['M'] ?? '')],
                    ['jabatan' => 'Kadus III', 'nama' => trim($row['N'] ?? '')],
                ];

                // Kadus IV
                if (!empty(trim($row['P'] ?? '')) && stripos(trim($row['O'] ?? ''), 'Tidak Ada') === false) {
                    $perangkatList[] = [
                        'jabatan' => 'Kadus IV', // or $row['O'] but 'Kadus IV' is standardized
                        'nama' => trim($row['P'])
                    ];
                }

                // Kadus V
                if (!empty(trim($row['R'] ?? '')) && stripos(trim($row['Q'] ?? ''), 'Tidak Ada') === false) {
                    $perangkatList[] = [
                        'jabatan' => 'Kadus V',
                        'nama' => trim($row['R'])
                    ];
                }

                // Staf Perangkat Desa (Row U = Nama, Row V = Jabatan)
                if (!empty(trim($row['U'] ?? '')) && stripos(trim($row['S'] ?? ''), 'Tidak Ada') === false) {
                    $perangkatList[] = [
                        'jabatan' => trim($row['V'] ?? 'Staf Perangkat Desa'),
                        'nama' => trim($row['U'])
                    ];
                }

                // Staf Non Perangkat Desa 1 (Row Y = Nama, Row Z = Jabatan)
                if (!empty(trim($row['Y'] ?? '')) && stripos(trim($row['W'] ?? ''), 'Tidak Ada') === false) {
                    $perangkatList[] = [
                        'jabatan' => trim($row['Z'] ?? 'Staf Non Perangkat Desa 1'),
                        'nama' => trim($row['Y'])
                    ];
                }

                // Staf Non Perangkat Desa 2 (Row AA = Nama, Row AB = Jabatan)
                if (!empty(trim($row['AA'] ?? '')) && trim($row['AA']) !== '-') {
                    $perangkatList[] = [
                        'jabatan' => trim($row['AB'] ?? 'Staf Non Perangkat Desa 2'),
                        'nama' => trim($row['AA'])
                    ];
                }

                // Staf Non Perangkat Desa 3 (Row AC = Nama, Row AD = Jabatan)
                if (!empty(trim($row['AC'] ?? '')) && trim($row['AC']) !== '-') {
                    $perangkatList[] = [
                        'jabatan' => trim($row['AD'] ?? 'Staf Non Perangkat Desa 3'),
                        'nama' => trim($row['AC'])
                    ];
                }

                // Insert into PerangkatDesa
                foreach ($perangkatList as $p) {
                    if (!empty($p['nama']) && $p['nama'] !== '-') {
                        PerangkatDesa::updateOrCreate(
                            [
                                'desa_id' => $desa->id,
                                'jabatan' => $p['jabatan']
                            ],
                            [
                                'nama' => $p['nama'],
                                'status_aktif' => true,
                                'tgl_mulai_jabatan' => now(), // Default just so it's filled
                            ]
                        );
                        $insertedCount++;
                    }
                }

                // We can also insert BPD members but the system schema (PerangkatDesa) right now supports device/staffs
                // We will insert BPD into the same table for now or just skip, User said "data perangkat desa" so we assume all

                $bpdList = [
                    // Ketua BPD (AJ = Nama, AI = Status 'Ada')
                    ['nama' => trim($row['AJ'] ?? ''), 'jabatan' => 'Ketua BPD', 'status' => trim($row['AI'] ?? '')],
                    // Wakil Ketua BPD (AL = Nama, AK = Status)
                    ['nama' => trim($row['AL'] ?? ''), 'jabatan' => 'Wakil Ketua BPD', 'status' => trim($row['AK'] ?? '')],
                    // Sekretaris BPD (AN = Nama, AM = Status)
                    ['nama' => trim($row['AN'] ?? ''), 'jabatan' => 'Sekretaris BPD', 'status' => trim($row['AM'] ?? '')],
                    // Ketua Bidang 1 (AP = Nama, AO = Status)
                    ['nama' => trim($row['AP'] ?? ''), 'jabatan' => 'Ketua Bid. Pemerintahan & Kemasyarakatan', 'status' => trim($row['AO'] ?? '')],
                    // Ketua Bidang 2 (AR = Nama, AQ = Status)
                    ['nama' => trim($row['AR'] ?? ''), 'jabatan' => 'Ketua Bid. Pembangunan & Pemberdayaan', 'status' => trim($row['AQ'] ?? '')],
                    // BPD Lainnya 1 (AT = Nama, AS = Status, AU = Jabatan)
                    ['nama' => trim($row['AT'] ?? ''), 'jabatan' => trim($row['AU'] ?? 'Anggota BPD 1'), 'status' => trim($row['AS'] ?? '')],
                    // BPD Lainnya 2 (AW = Nama, AV = Status, AX = Jabatan)
                    ['nama' => trim($row['AW'] ?? ''), 'jabatan' => trim($row['AX'] ?? 'Anggota BPD 2'), 'status' => trim($row['AV'] ?? '')],
                    // BPD Lainnya 3 (AZ = Nama, AY = Status, BA = Jabatan)
                    ['nama' => trim($row['AZ'] ?? ''), 'jabatan' => trim($row['BA'] ?? 'Anggota BPD 3'), 'status' => trim($row['AY'] ?? '')],
                    // BPD Lainnya 4 (BC = Nama, BB = Status, BD = Jabatan)
                    ['nama' => trim($row['BC'] ?? ''), 'jabatan' => trim($row['BD'] ?? 'Anggota BPD 4'), 'status' => trim($row['BB'] ?? '')],
                ];

                foreach ($bpdList as $b) {
                    if (!empty($b['nama']) && $b['nama'] !== '-' && stripos($b['status'], 'Ada') !== false) {
                        PerangkatDesa::updateOrCreate(
                            [
                                'desa_id' => $desa->id,
                                'jabatan' => $b['jabatan']
                            ],
                            [
                                'nama' => $b['nama'],
                                'status_aktif' => true,
                                'tgl_mulai_jabatan' => now(),
                            ]
                        );
                        $insertedCount++;
                    }
                }
            }
        });

        $this->info("Berhasil mengimpor $insertedCount data perangkat desa/BPD.");
    }
}
