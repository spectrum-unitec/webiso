<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableHistoryDocument extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'document_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => [
                    'create',
                    'update'
                ]
            ],
            'no_document' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'nama_document' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'created_at' => [
                'type' => 'DATETIME',

            ],
            'updated_at' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->forge->addKey('id', true);

        // Foreign Keys
        $this->forge->addForeignKey('document_id', 'my_documents', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('history_documents');
    }

    public function down()
    {
        $this->forge->dropTable('history_documents');
    }
}
