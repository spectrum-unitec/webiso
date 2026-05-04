<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnDocumentRequest extends Migration
{
    public function up()
    {
        $this->forge->addColumn('document_requests', [
            'note' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status'
            ],
            'deleted_at' => [
                'type' => 'DATETIME'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('document_requests', 'note');
        $this->forge->dropColumn('document_requests', 'deleted_at');
    }
}
