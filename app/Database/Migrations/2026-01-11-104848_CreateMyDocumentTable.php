<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMyDocumentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true, 'constraint' => 11],
            'no_document'       => ['type' => 'VARCHAR', 'constraint' => 200],
            'nama_document'    => ['type' => 'VARCHAR', 'constraint' => 200],
            'level_id'          => ['type' => 'INT', 'unsigned' => true],
            'jenis_id'          => ['type' => 'INT', 'unsigned' => true],
            'divisi_id'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'file'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true]
        ]);

        $this->forge->addKey('id', true);

        // Foreign Keys
        $this->forge->addForeignKey('level_id', 'document_level_iso', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jenis_id', 'document_jenis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('divisi_id', 'divisis', 'id', 'SET NULL', 'CASCADE');


        $this->forge->createTable('my_documents');
    }

    public function down()
    {
        $this->forge->dropTable('my_documents');
    }
}
