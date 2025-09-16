<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PembelianBahanModel;
use App\Models\PembelianBahanItemModel;
use App\Models\BahanBakuModel;

class PembelianBahanController extends BaseController
{
    protected $pembelianBahanModel;
    protected $pembelianBahanItemModel;
    protected $bahanBakuModel;

    public function __construct()
    {
        $this->pembelianBahanModel = new PembelianBahanModel();
        $this->pembelianBahanItemModel = new PembelianBahanItemModel();
        $this->bahanBakuModel = new BahanBakuModel();
    }

    public function index()
    {
        $data['pembelianBahan'] = $this->pembelianBahanModel
                        ->orderBy('tanggal_pembelian', 'DESC')
                        ->findAll();
        $data['bahanBaku'] = $this->bahanBakuModel->findAll();
        return view('pembelian-bahan/index', $data);
    }
    
    public function detail($pembelianId)
    {
        $data['pembelian'] = $this->pembelianBahanModel->find($pembelianId);
        
        if (!$data['pembelian']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['items'] = $this->pembelianBahanItemModel
                              ->where('pembelian_id', $pembelianId)
                              ->findAll();
        
        return view('pembelian-bahan/detail', $data);
    }

    public function store()
    {
        $namaPembelian = $this->request->getPost('nama_pembelian');
        $tanggalPembelian = $this->request->getPost('tanggal_pembelian');
        $sourceMoney = $this->request->getPost('source_money');
        $adminFee = $this->request->getPost('admin_fee');
        $discount = $this->request->getPost('discount');
        $hargaTotal = $this->request->getPost('harga_total');
        
        if (empty($namaPembelian) || empty($tanggalPembelian)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama dan Tanggal Pembelian Wajib Diisi']);
        }
        
        if (empty($sourceMoney)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Source Money Wajib Diisi']);
        }
        
        $bahanBakuIds = $this->request->getPost('bahan_baku_id');
        $itemQuantities = $this->request->getPost('jumlah_item');
        $isiPerUnit = $this->request->getPost('isi_per_unit');
        $itemPrices = $this->request->getPost('harga_item');
        $hargaPerUnit = $this->request->getPost('harga_per_unit');
        
        if (empty($bahanBakuIds)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Minimal 1 item harus diisi.']);
        }
        
        $dataPembelian = [
            'nama_pembelian' => $namaPembelian,
            'tanggal_pembelian' => $tanggalPembelian,
            'source_money' => $sourceMoney,
            'admin_fee' => $adminFee,
            'discount' => $discount,
            'harga_total' => $hargaTotal
        ];

        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $pembelianId = $this->pembelianBahanModel->insert($dataPembelian);
            
            if ($pembelianId) {
                $itemsToInsert = [];
                
                foreach ($bahanBakuIds as $key => $bahanBakuId) {
                    // Get nama_bahan from bahan_baku table
                    $bahanBaku = $this->bahanBakuModel->find($bahanBakuId);
                    $namaItem = $bahanBaku ? $bahanBaku['nama_bahan'] : 'Unknown';
                    
                    $quantity = $itemQuantities[$key];
                    $isi = $isiPerUnit[$key];
                    $totalStok = $quantity * $isi; // Total pieces to add to stock
                    
                    $itemsToInsert[] = [
                        'pembelian_id' => $pembelianId,
                        'bahan_baku_id' => $bahanBakuId,
                        'nama_item' => $namaItem,
                        'jumlah_item' => $quantity,
                        'isi_per_unit' => $isi,
                        'harga_item' => $itemPrices[$key],
                        'harga_per_unit' => $hargaPerUnit[$key],
                    ];
                    
                    $currentBahanBaku = $this->bahanBakuModel->find($bahanBakuId);
                    if ($currentBahanBaku) {
                        $currentStok = $currentBahanBaku['stok'] ?? 0;
                        $newStok = $currentStok + $totalStok;
                        $newHpp = $hargaPerUnit[$key];
                        
                        $this->bahanBakuModel->update($bahanBakuId, [
                            'stok' => $newStok,
                            'hpp' => $newHpp
                        ]);
                    }
                }
                
                $this->pembelianBahanItemModel->insertBatch($itemsToInsert);
                
                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Data.']);
                }

                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Pembelian Bahan Berhasil Disimpan!',
                    'data' => ['pembelian_id' => $pembelianId]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Pembelian Bahan.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    public function update()
    {
        $pembelianId = $this->request->getPost('pembelian_id');
        $namaPembelian = $this->request->getPost('nama_pembelian');
        $tanggalPembelian = $this->request->getPost('tanggal_pembelian');
        $sourceMoney = $this->request->getPost('source_money');
        $adminFee = $this->request->getPost('admin_fee');
        $discount = $this->request->getPost('discount');
        $itemsJson = $this->request->getPost('items');
        
        if (empty($pembelianId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Pembelian Wajib Diisi']);
        }
        
        if (empty($namaPembelian) || empty($tanggalPembelian)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama dan Tanggal Pembelian Wajib Diisi']);
        }
        
        $existingPembelian = $this->pembelianBahanModel->find($pembelianId);
        if (!$existingPembelian) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pembelian Bahan Tidak Ditemukan']);
        }
        
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $dataUpdate = [
                'nama_pembelian' => $namaPembelian,
                'tanggal_pembelian' => $tanggalPembelian,
                'source_money' => $sourceMoney,
                'admin_fee' => $adminFee,
                'discount' => $discount
            ];
            
            $this->pembelianBahanModel->update($pembelianId, $dataUpdate);
            
            if (!empty($itemsJson)) {
                $items = json_decode($itemsJson, true);
                
                $existingItems = $this->pembelianBahanItemModel->where('pembelian_id', $pembelianId)->findAll();
                
                foreach ($existingItems as $oldItem) {
                    if ($oldItem['bahan_baku_id']) {
                        $currentBahanBaku = $this->bahanBakuModel->find($oldItem['bahan_baku_id']);
                        if ($currentBahanBaku) {
                            $oldTotalStok = ($oldItem['jumlah_item'] ?? 0) * ($oldItem['isi_per_unit'] ?? 1);
                            $currentStok = $currentBahanBaku['stok'] ?? 0;
                            $newStok = max(0, $currentStok - $oldTotalStok);
                            
                            $this->bahanBakuModel->update($oldItem['bahan_baku_id'], ['stok' => $newStok]);
                        }
                    }
                }
                
                foreach ($items as $item) {
                    $itemData = [
                        'bahan_baku_id' => $item['bahan_baku_id'],
                        'jumlah_item' => $item['jumlah_item'],
                        'isi_per_unit' => $item['isi_per_unit'],
                        'harga_item' => $item['harga_item'],
                        'harga_per_unit' => $item['harga_per_unit']
                    ];
                    
                    if ($item['bahan_baku_id']) {
                        $bahanBaku = $this->bahanBakuModel->find($item['bahan_baku_id']);
                        $itemData['nama_item'] = $bahanBaku ? $bahanBaku['nama_bahan'] : 'Unknown';
                    }
                    
                    $this->pembelianBahanItemModel->update($item['id'], $itemData);
                    
                    if ($item['bahan_baku_id']) {
                        $currentBahanBaku = $this->bahanBakuModel->find($item['bahan_baku_id']);
                        if ($currentBahanBaku) {
                            $newTotalStok = ($item['jumlah_item'] ?? 0) * ($item['isi_per_unit'] ?? 1);
                            $currentStok = $currentBahanBaku['stok'] ?? 0;
                            $updatedStok = $currentStok + $newTotalStok;
                            
                            $this->bahanBakuModel->update($item['bahan_baku_id'], [
                                'stok' => $updatedStok,
                                'hpp' => $item['harga_per_unit']
                            ]);
                        }
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Mengupdate Data']);
            }
            
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Pembelian Bahan Berhasil Diupdate!'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    public function delete()
    {
        $pembelianId = $this->request->getPost('id');
        
        if (empty($pembelianId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Pembelian Wajib Diisi']);
        }
        
        $existingPembelian = $this->pembelianBahanModel->find($pembelianId);
        if (!$existingPembelian) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pembelian Bahan Tidak Ditemukan']);
        }
        
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $items = $this->pembelianBahanItemModel->where('pembelian_id', $pembelianId)->findAll();
            
            foreach ($items as $item) {
                if ($item['bahan_baku_id']) {
                    $currentBahanBaku = $this->bahanBakuModel->find($item['bahan_baku_id']);
                    if ($currentBahanBaku) {
                        $quantity = $item['jumlah_item'];
                        $isiPerUnit = $item['isi_per_unit'] ?? 1;
                        $totalStokToRemove = $quantity * $isiPerUnit;
                        
                        $currentStok = $currentBahanBaku['stok'] ?? 0;
                        $newStok = max(0, $currentStok - $totalStokToRemove);
                        
                        $this->bahanBakuModel->update($item['bahan_baku_id'], [
                            'stok' => $newStok,
                            'hpp' => null
                        ]);
                    }
                }
            }
            
            $this->pembelianBahanItemModel->where('pembelian_id', $pembelianId)->delete();
            
            $deleted = $this->pembelianBahanModel->delete($pembelianId);
            
            $db->transComplete();
            
            if ($db->transStatus() === false || !$deleted) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menghapus Pembelian Bahan']);
            }
            
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Pembelian Bahan Berhasil Dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}