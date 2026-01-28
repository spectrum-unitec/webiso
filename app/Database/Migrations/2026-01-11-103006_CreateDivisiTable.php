<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDivisiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_divisi' => ['type' => 'VARCHAR', 'constraint' => 100],
            'nama_divisi' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('divisis');
    }

    public function down()
    {
        $this->forge->dropTable('divisis');
    }
}
