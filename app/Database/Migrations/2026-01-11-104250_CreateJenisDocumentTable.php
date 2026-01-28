<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisDocumentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true, 'constraint' => 11],
            'jenis_document'    => ['type' => 'VARCHAR', 'constraint' => 200],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('document_jenis');
    }

    public function down()
    {
        $this->forge->dropTable('document_jenis');
    }
}
