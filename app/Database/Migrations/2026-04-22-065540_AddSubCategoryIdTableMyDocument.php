<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSubCategoryIdTableMyDocument extends Migration
{
    public function up()
    {
        $this->forge->addColumn('my_documents', [
            'sub_category_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'after' => 'divisi_id',
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('my_documents', 'sub_category_id');
    }
}
