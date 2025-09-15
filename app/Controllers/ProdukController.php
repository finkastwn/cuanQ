<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    protected $incomeCategoryModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data['produk'] = $this->produkModel
                        ->findAll();
        return view('produk/index', $data);
    }

    public function isExist($nama_produk)
    {
        $duplicateCheck = $this->produkModel
                              ->where('nama_produk', $nama_produk)
                              ->first();
        
        return $duplicateCheck !== null;
    }

    public function store_produk()
    {
        $nama_produk = $this->request->getPost('nama_produk');
        $harga_produk = $this->request->getPost('harga_produk');
        
        if (empty($nama_produk)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Produk Wajib Diisi']);
        }

        if (empty($harga_produk)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Harga Produk Wajib Diisi']);
        }
        
        if ($this->isExist($nama_produk)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Produk Sudah Terdaftar!']);
        }
        
        $data = [
            'nama_produk' => $nama_produk,
            'harga_produk' => $harga_produk
        ];

        try {
            $result = $this->produkModel->insert($data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Produk Berhasil Ditambahkan!',
                    'data' => [
                        'id' => $result,
                        'nama_produk' => $nama_produk,
                        'harga_produk' => $harga_produk
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menambahkan Produk']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menambahkan Produk. Silahkan Coba Lagi!']);
        }
    }
}
