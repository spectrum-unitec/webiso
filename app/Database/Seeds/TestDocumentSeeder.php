<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDocumentSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        for ($i = 1; $i < 100; $i++) {
            $data[] = [
                'no_document' => rand(1, 5),
                'slug' => 'dokumen-' . $i,
                'nama_document' => 'Dokumen  ' . $i,
                'level_id' => 2,
                'jenis_id' => 2,
                'divisi_id' => 2,
                'file' => 'tes' . $i . 'pdf',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('my_documents')->insertBatch($data);
    }
}
