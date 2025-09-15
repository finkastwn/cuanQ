<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BahanBakuModel;

class BahanBakuController extends BaseController
{
    public function __construct()
    {
        $this->bahanBakuModel = new BahanBakuModel();
    }

    public function index()
    {
        $data['bahan_baku'] = $this->bahanBakuModel
                        ->findAll();
        return view('bahan-baku/index', $data);
    }

    public function isExist($nama_bahan)
    {
        $duplicateCheck = $this->bahanBakuModel
                              ->where('nama_bahan', $nama_bahan)
                              ->first();
        
        return $duplicateCheck !== null;
    }

    public function store()
    {
        $nama_bahan = $this->request->getPost('nama_bahan');
        
        if (empty($nama_bahan)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Bahan Baku Wajib Diisi']);
        }

        if ($this->isExist($nama_bahan)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Bahan Baku Sudah Terdaftar!']);
        }
        
        $data = [
            'nama_bahan' => $nama_bahan
        ];

        try {
            $result = $this->bahanBakuModel->insert($data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Bahan Baku Berhasil Ditambahkan!',
                    'data' => [
                        'id' => $result,
                        'nama_bahan' => $nama_bahan,
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menambahkan Bahan Baku']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menambahkan Bahan Baku. Silahkan Coba Lagi!']);
        }
    }

    public function update()
    {
        $bahanBakuId = $this->request->getPost('bahan_baku_id');
        $nama_bahan = $this->request->getPost('nama_bahan');
        
        if (empty($bahanBakuId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Bahan Baku Wajib Diisi']);
        }

        if (empty($nama_bahan)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Bahan Baku Wajib Diisi']);
        }

        $existingBahanBaku = $this->bahanBakuModel->find($bahanBakuId);
        if (!$existingBahanBaku) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Bahan Baku Tidak Ditemukan']);
        }
        
        $duplicateCheck = $this->bahanBakuModel
                              ->where('nama_bahan', $nama_bahan)
                              ->where('id !=', $bahanBakuId)
                              ->first();
        
        if ($duplicateCheck) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Bahan Baku Sudah Terdaftar!']);
        }
        
        $data = [
            'nama_bahan' => $nama_bahan
        ];

        try {
            $result = $this->bahanBakuModel->update($bahanBakuId, $data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Bahan Baku Berhasil Diupdate!',
                    'data' => [
                        'id' => $bahanBakuId,
                        'nama_bahan' => $nama_bahan
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Mengupdate Bahan Baku']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $bahanBakuId = $this->request->getPost('bahan_baku_id');
        
        if (empty($bahanBakuId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Bahan Baku Wajib Diisi']);
        }

        $existingBahanBaku = $this->bahanBakuModel->find($bahanBakuId);
        if (!$existingBahanBaku) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Bahan Baku Tidak Ditemukan']);
        }

        try {
            $result = $this->bahanBakuModel->delete($bahanBakuId);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Bahan Baku Berhasil Dihapus!',
                    'data' => [
                        'id' => $bahanBakuId,
                        'nama_bahan' => $existingBahanBaku['nama_bahan']
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menghapus Bahan Baku']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
