<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLevelDocumentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true, 'constraint' => 11],
            'level'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('document_level_iso');
    }

    public function down()
    {
        $this->forge->dropTable('document_level_iso');
    }
}
