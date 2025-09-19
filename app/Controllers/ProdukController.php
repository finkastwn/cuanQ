<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    protected $produkModel;

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
        $biaya_pajak_persen = $this->request->getPost('biaya_pajak_persen') ?? 0;
        $komisi_affiliate_persen = $this->request->getPost('komisi_affiliate_persen') ?? 0;
        
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
            'harga_produk' => $harga_produk,
            'harga_final' => $harga_produk, 
            'biaya_pajak_persen' => $biaya_pajak_persen,
            'komisi_affiliate_persen' => $komisi_affiliate_persen
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

    public function update_produk()
    {
        $produkId = $this->request->getPost('produk_id');
        $nama_produk = $this->request->getPost('nama_produk');
        $harga_produk = $this->request->getPost('harga_produk');
        $biaya_pajak_persen = $this->request->getPost('biaya_pajak_persen') ?? 0;
        $komisi_affiliate_persen = $this->request->getPost('komisi_affiliate_persen') ?? 0;
        
        if (empty($produkId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Produk Wajib Diisi']);
        }

        if (empty($nama_produk)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Produk Wajib Diisi']);
        }

        if (empty($harga_produk)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Harga Produk Wajib Diisi']);
        }

        $existingProduct = $this->produkModel->find($produkId);
        if (!$existingProduct) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk Tidak Ditemukan']);
        }
        
        $duplicateCheck = $this->produkModel
                              ->where('nama_produk', $nama_produk)
                              ->where('id !=', $produkId)
                              ->first();
        
        if ($duplicateCheck) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama Produk Sudah Terdaftar!']);
        }

        $harga_final = $harga_produk; // Default to new price
        
        if (!empty($existingProduct['promo_type']) && $existingProduct['promo_type'] !== 'none' && $existingProduct['promo_active'] == 1) {
            if ($existingProduct['promo_type'] === 'percent') {
                $discountAmount = ($harga_produk * intval($existingProduct['promo_value'])) / 100;
                $harga_final = $harga_produk - $discountAmount;
            } elseif ($existingProduct['promo_type'] === 'fixed') {
                $harga_final = $harga_produk - intval($existingProduct['promo_value']);
            }
            $harga_final = max(0, $harga_final);
        }
        
        $data = [
            'nama_produk' => $nama_produk,
            'harga_produk' => $harga_produk,
            'harga_final' => $harga_final,
            'biaya_pajak_persen' => $biaya_pajak_persen,
            'komisi_affiliate_persen' => $komisi_affiliate_persen
        ];

        try {
            $result = $this->produkModel->update($produkId, $data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Produk Berhasil Diupdate!',
                    'data' => [
                        'id' => $produkId,
                        'nama_produk' => $nama_produk,
                        'harga_produk' => $harga_produk,
                        'harga_final' => $harga_final
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Mengupdate Produk']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete_produk()
    {
        $produkId = $this->request->getPost('produk_id');
        
        if (empty($produkId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID Produk Wajib Diisi']);
        }

        $existingProduct = $this->produkModel->find($produkId);
        if (!$existingProduct) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk Tidak Ditemukan']);
        }

        try {
            $result = $this->produkModel->delete($produkId);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Produk Berhasil Dihapus!',
                    'data' => [
                        'id' => $produkId,
                        'nama_produk' => $existingProduct['nama_produk']
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menghapus Produk']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function store_promo()
    {
        $produkId = $this->request->getPost('produk_id');
        $promoType = $this->request->getPost('promo_type');
        $promoValue = $this->request->getPost('promo_value');
        $promoActive = $this->request->getPost('promo_active');
        $promoStart = $this->request->getPost('promo_start');
        $promoEnd = $this->request->getPost('promo_end');
        $finalPrice = $this->request->getPost('final_price');

        if (empty($produkId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk ID Wajib Diisi']);
        }

        if (empty($promoType)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tipe Promo Wajib Dipilih']);
        }

        if (empty($promoValue)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nilai Promo Wajib Diisi']);
        }

        if (empty($promoStart) || empty($promoEnd)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggal Mulai dan Berakhir Wajib Diisi']);
        }

        $startDate = new \DateTime($promoStart);
        $endDate = new \DateTime($promoEnd);
        
        if ($endDate <= $startDate) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggal Berakhir Harus Setelah Tanggal Mulai']);
        }

        $existingProduct = $this->produkModel->find($produkId);
        if (!$existingProduct) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk Tidak Ditemukan']);
        }

        $originalPrice = $existingProduct['harga_produk'];
        $finalPrice = $originalPrice;
        
        if ($promoType === 'percent') {
            $discountAmount = ($originalPrice * intval($promoValue)) / 100;
            $finalPrice = $originalPrice - $discountAmount;
        } elseif ($promoType === 'fixed') {
            $finalPrice = $originalPrice - intval($promoValue);
        }
        
        $finalPrice = max(0, $finalPrice);

        $data = [
            'promo_type' => $promoType,
            'promo_value' => $promoValue,
            'promo_active' => $promoActive,
            'promo_start' => $promoStart,
            'promo_end' => $promoEnd,
            'harga_final' => $finalPrice
        ];

        try {
            $result = $this->produkModel->update($produkId, $data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Promo Berhasil Disimpan!',
                    'data' => [
                        'produk_id' => $produkId,
                        'promo_type' => $promoType,
                        'promo_value' => $promoValue,
                        'promo_active' => $promoActive,
                        'promo_start' => $promoStart,
                        'promo_end' => $promoEnd,
                        'final_price' => $finalPrice
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menyimpan Promo - Update returned false']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function view_promo($produkId)
    {
        try {
            $product = $this->produkModel->find($produkId);
            
            if (!$product) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Produk Tidak Ditemukan']);
            }

            $hasPromo = !empty($product['promo_type']) && $product['promo_type'] !== 'none';
            
            if ($hasPromo) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'id' => $product['id'],
                        'nama_produk' => $product['nama_produk'],
                        'harga_produk' => $product['harga_produk'],
                        'promo_type' => $product['promo_type'],
                        'promo_value' => $product['promo_value'],
                        'promo_active' => $product['promo_active'],
                        'promo_start' => $product['promo_start'],
                        'promo_end' => $product['promo_end'],
                        'final_price' => $product['harga_final'] ?? $product['harga_produk'],
                        'has_promo' => true
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'id' => $product['id'],
                        'nama_produk' => $product['nama_produk'],
                        'harga_produk' => $product['harga_produk'],
                        'has_promo' => false
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function delete_promo()
    {
        $produkId = $this->request->getPost('produk_id');

        if (empty($produkId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk ID Wajib Diisi']);
        }

        $existingProduct = $this->produkModel->find($produkId);
        if (!$existingProduct) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk Tidak Ditemukan']);
        }

        $data = [
            'promo_type' => 'none',
            'promo_value' => 0,
            'promo_active' => 0,
            'promo_start' => null,
            'promo_end' => null,
            'harga_final' => $existingProduct['harga_produk'] // Reset to original price
        ];

        try {
            $result = $this->produkModel->update($produkId, $data);
            
            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Promo Berhasil Dihapus!',
                    'data' => [
                        'produk_id' => $produkId
                    ]
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal Menghapus Promo']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
