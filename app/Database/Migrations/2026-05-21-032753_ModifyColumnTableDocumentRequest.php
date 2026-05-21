<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyColumnTableDocumentRequest extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('document_requests', [
            'no_doc' => ['type' => 'VARCHAR', 'constraint' => 155, 'null' => true],
            'revisi' => ['type' => 'VARCHAR', 'constraint' => 155, 'null' => true],
        ]);
    }

    public function down()
    {
        // isi null jadi string kosong
        $this->db->query("
        UPDATE document_requests
        SET no_doc = ''
        WHERE no_doc IS NULL
    ");

        $this->db->query("
        UPDATE document_requests
        SET revisi = ''
        WHERE revisi IS NULL
    ");

        // ubah kembali NOT NULL
        $this->forge->modifyColumn('document_requests', [

            'no_doc' => [
                'type'       => 'VARCHAR',
                'constraint' => 155,
                'null'       => false,
            ],

            'revisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 155,
                'null'       => false,
            ],

        ]);
    }
}
