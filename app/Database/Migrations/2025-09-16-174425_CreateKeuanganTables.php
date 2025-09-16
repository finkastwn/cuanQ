<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKeuanganTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['pemasukan', 'pengeluaran'],
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kategori' => [
                'type'       => 'ENUM',
                'constraint' => ['manual', 'pesanan', 'pembelian_bahan'],
                'default'    => 'manual',
            ],
            'reference_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('manual_transactions');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'utang_total' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'bank_account_balance' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'shopee_pocket_balance' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'last_updated' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('financial_summary');

        $this->db->table('financial_summary')->insert([
            'utang_total' => 0,
            'bank_account_balance' => 0,
            'shopee_pocket_balance' => 0,
            'last_updated' => date('Y-m-d H:i:s')
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('manual_transactions');
        $this->forge->dropTable('financial_summary');
    }
}