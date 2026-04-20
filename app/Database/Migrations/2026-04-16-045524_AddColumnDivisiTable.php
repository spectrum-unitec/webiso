<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnDivisiTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('divisis', [
            'department_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'after' => 'id',
                'null'=> true
            ]
        ]);

        $this->forge->addForeignKey('department_id', 'departments', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('divisis', 'department_id');
    }
}
