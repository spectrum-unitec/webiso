<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToJenisDocumentTable extends Migration
{
    public function up()
    {
        $fields = [
            'slug' => ['type' => 'VARCHAR', 'constraint' => 200, 'after' => 'id', 'unique' => true]
        ];
        $this->forge->addColumn('document_jenis', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('document_jenis', 'slug');
    }
}
