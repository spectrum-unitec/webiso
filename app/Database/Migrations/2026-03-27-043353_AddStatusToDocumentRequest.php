<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToDocumentRequest extends Migration
{
    public function up()
    {
        $this->forge->addColumn('document_requests', [
            'status' => ['type' => 'ENUM', 'constraint' => ['approved', 'reject', 'pratinjau'], 'after' => 'usulan', 'default' => 'pratinjau']
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('document_requests', 'status');
    }
}
