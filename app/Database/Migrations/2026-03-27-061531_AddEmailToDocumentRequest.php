<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToDocumentRequest extends Migration
{

    public function up()
    {
        $this->forge->addColumn('document_requests', [
            'email' => ['type' => 'VARCHAR', 'after' => 'nama_user', 'constraint' => 100]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('document_requests', 'email');
    }
}
