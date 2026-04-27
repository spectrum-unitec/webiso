<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableDocumentTerkait extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'document_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
        ]);

        $this->forge->addForeignKey('document_id', 'my_documents', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pivot_document_terkait');
    }

    public function down()
    {
        $this->forge->dropTable('pivot_document_terkait');
    }
}
