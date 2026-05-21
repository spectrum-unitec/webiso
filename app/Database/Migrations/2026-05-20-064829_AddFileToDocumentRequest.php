<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFileToDocumentRequest extends Migration
{
    public function up()
    {
        $this->forge->addColumn('document_requests', [
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'after' => 'usulan',
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('document_requests', 'file');
    }
}
