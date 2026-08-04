<?php

namespace Database\Seeders;

use App\Models\TemplateChecklistBpd;
use Illuminate\Database\Seeder;

class TemplateChecklistBpdSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            '1. Dokumen A',
            '2. Dokumen B',
            '3. Dokumen C',
            '4. Dokumen D',
            '5. Dokumen E',
            '6. Dokumen F',
            '7. Dokumen G',
            '8. Dokumen H',
            '9. Dokumen I',
            '10. Dokumen J',
            '11. Dokumen K',
            '12. Dokumen L',
            '13. Dokumen M',
        ];

        foreach ($documents as $index => $doc) {
            TemplateChecklistBpd::create([
                'nama_dokumen' => $doc,
                'wajib' => true,
                'urutan' => $index + 1,
            ]);
        }
    }
}
