<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSubCategoryNonIso extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'jenis_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 150
            ],
            'created_at' => [
                'type' => 'DATETIME',

            ],
            'updated_at' => [
                'type' => 'DATETIME'
            ]

        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('jenis_id', 'document_jenis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sub_categories');
    }

    public function down()
    {
        $this->forge->dropTable('sub_categories');
    }
}
