<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PembelianBahanModel;

class PembelianBahanController extends BaseController
{
    protected $pembelianBahanModel;

    public function __construct()
    {
        $this->pembelianBahanModel = new PembelianBahanModel();
    }

    public function index()
    {
        $data['pembelianBahan'] = $this->pembelianBahanModel
                        ->orderBy('tanggal_pembelian', 'DESC')
                        ->findAll();
        return view('pembelian-bahan/index', $data);
    }

    public function store()
    {
        $pembelianId = $this->request->getPost('pembelian_id');
        $namaPembelian = $this->request->getPost('nama_pembelian');
        $tanggalPembelian = $this->request->getPost('tanggal_pembelian');

        if (empty($namaPembelian)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Pembelian Wajib Diisi']);
        }

        if (empty($tanggalPembelian)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggal Pembelian Wajib Dipilih']);
        }

        $data = [
            'nama_pembelian' => $namaPembelian,
            'tanggal_pembelian' => $tanggalPembelian
        ];

        try {
            $result = $this->pembelianBahanModel->insert($data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Pembelian Bahan Berhasil Disimpan!',
                    'data' => [
                        'pembelian_id' => $pembelianId,
                        'nama_pembelian' => $namaPembelian,
                        'tanggal_pembelian' => $tanggalPembelian
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Pembelian Bahan - Update returned false']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}