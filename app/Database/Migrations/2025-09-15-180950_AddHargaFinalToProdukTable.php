<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargaFinalToProdukTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('produk', [
            'harga_final' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'harga_produk'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('produk', 'harga_final');
    }
}
