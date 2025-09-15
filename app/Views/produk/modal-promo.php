<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="promoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Atur Promo Produk</h2>
            <span class="close" onclick="closePromoModal()">&times;</span>
        </div>
        
        <form id="promoForm">
            <input type="hidden" id="promo_produk_id" name="produk_id">
            
            <div class="form-group">
                <label class="form-label">Nama Produk</label>
                <input type="text" id="promo_nama_produk" class="form-input" readonly>
                
                <label class="form-label">Harga Asli</label>
                <div class="currency-input-wrapper">
                    <span class="currency-prefix">Rp</span>
                    <input type="text" id="promo_harga_asli" class="form-input currency-input" readonly>
                </div>
                
                <div class="promo-row">
                    <div class="promo-field">
                        <label for="promo_type" class="form-label">Tipe Promo</label>
                        <select id="promo_type" name="promo_type" class="form-input" required>
                            <option value="">Pilih Tipe Promo</option>
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div class="promo-field">
                        <label for="promo_value" class="form-label">Nilai Promo</label>
                        <div id="promo_value_wrapper">
                            <input type="text" 
                                id="promo_value" 
                                name="promo_value" 
                                class="form-input"
                                placeholder="Masukkan nilai promo"
                                required>
                        </div>
                    </div>
                </div>
                
                <label for="promo_active" class="form-label">Status Promo</label>
                <select id="promo_active" name="promo_active" class="form-input" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
                
                <div class="date-row">
                    <div class="date-field">
                        <label for="promo_start" class="form-label">Tanggal Mulai</label>
                        <input type="datetime-local" 
                            id="promo_start" 
                            name="promo_start" 
                            class="form-input"
                            required>
                    </div>
                    <div class="date-field">
                        <label for="promo_end" class="form-label">Tanggal Berakhir</label>
                        <input type="datetime-local" 
                            id="promo_end" 
                            name="promo_end" 
                            class="form-input"
                            required>
                    </div>
                </div>
                
                <div class="final-price-section">
                    <label class="form-label">Harga Final</label>
                    <div class="currency-input-wrapper">
                        <span class="currency-prefix">Rp</span>
                        <input type="text" id="final_price" class="form-input currency-input final-price-input" readonly>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closePromoModal()">Kembali</button>
                <button type="button" id="deletePromoBtn" class="btn-delete-promo" onclick="deletePromo()" style="display: none;">Hapus Promo</button>
                <button type="submit" class="btn-submit">Simpan Promo</button>
            </div>
        </form>
    </div>
</div>

<style>
    .final-price-section {
        margin-top: 20px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 2px solid #28a745;
    }
    
    .final-price-input {
        font-weight: bold;
        font-size: 18px;
        color: #28a745;
        background-color: white;
    }
    
    .promo-value-percentage {
        position: relative;
    }
    
    .promo-value-percentage::after {
        content: '%';
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-weight: 600;
        pointer-events: none;
    }
    
    .promo-value-currency {
        padding-left: 45px !important;
    }
    
    .date-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .date-field {
        flex: 1;
    }
    
    .date-field .form-input {
        margin-bottom: 0;
    }
    
    .promo-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .promo-field {
        flex: 1;
    }
    
    .promo-field .form-input {
        margin-bottom: 0;
    }
    
    .btn-delete-promo {
        background-color: transparent;
        color: #dc3545;
        border: 2px solid #dc3545;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-delete-promo:hover {
        background-color: #dc3545;
        color: #fff;
    }
</style>

<script>
    let currentPromoProductId = null;
    let currentProductPrice = 0;
    
    function openPromoModal(productId, productName, productPrice) {
        currentPromoProductId = productId;
        currentProductPrice = parseInt(productPrice);
        
        // First, check if product has existing promo
        fetch(`<?= base_url('produk/promo/view') ?>/${productId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                loadPromoData(data.data);
            } else {
                showSnackbar(data.message || 'Gagal Memuat Data Promo', 'error');
            }
        })
        .catch(() => {
            // If error, just open with default values
            loadPromoData({
                id: productId,
                nama_produk: productName,
                harga_produk: productPrice,
                has_promo: false
            });
        });
    }
    
    function loadPromoData(productData) {
        // Reset form first
        document.getElementById('promoForm').reset();
        
        // Set basic product info
        document.getElementById('promo_produk_id').value = productData.id;
        document.getElementById('promo_nama_produk').value = productData.nama_produk;
        document.getElementById('promo_harga_asli').value = parseInt(productData.harga_produk).toLocaleString('id-ID');
        
        if (productData.has_promo) {
            // Load existing promo data
            document.getElementById('promo_type').value = productData.promo_type;
            document.getElementById('promo_active').value = productData.promo_active;
            
            // Format dates for datetime-local input
            if (productData.promo_start) {
                const startDate = new Date(productData.promo_start);
                document.getElementById('promo_start').value = formatDateTimeLocal(startDate);
            }
            if (productData.promo_end) {
                const endDate = new Date(productData.promo_end);
                document.getElementById('promo_end').value = formatDateTimeLocal(endDate);
            }
            
            // Trigger promo type change to set up the value input correctly
            document.getElementById('promo_type').dispatchEvent(new Event('change'));
            
            // Set promo value after a short delay to ensure input is ready
            setTimeout(() => {
                document.getElementById('promo_value').value = productData.promo_value;
                if (productData.promo_type === 'fixed') {
                    document.getElementById('promo_value').value = parseInt(productData.promo_value).toLocaleString('id-ID');
                }
                calculateFinalPrice();
            }, 100);
            
            // Show delete button
            document.getElementById('deletePromoBtn').style.display = 'inline-block';
            
            // Update modal title
            document.querySelector('.modal-title').textContent = 'Edit Promo Produk';
        } else {
            // No existing promo, set defaults
            document.getElementById('promo_active').value = '1';
            
            // Set default dates
            const now = new Date();
            const tomorrow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
            const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
            
            document.getElementById('promo_start').value = formatDateTimeLocal(tomorrow);
            document.getElementById('promo_end').value = formatDateTimeLocal(nextWeek);
            
            // Hide delete button
            document.getElementById('deletePromoBtn').style.display = 'none';
            
            // Update modal title
            document.querySelector('.modal-title').textContent = 'Atur Promo Produk';
        }
        
        calculateFinalPrice();
        document.getElementById('promoModal').style.display = 'block';
    }
    
    function formatDateTimeLocal(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    
    function closePromoModal() {
        document.getElementById('promoModal').style.display = 'none';
        document.getElementById('promoForm').reset();
        currentPromoProductId = null;
        currentProductPrice = 0;
    }
    
    function deletePromo() {
        if (!currentPromoProductId) {
            showSnackbar('ID Produk Tidak Ditemukan', 'error');
            return;
        }
        
        if (!confirm('Apakah Anda yakin ingin menghapus promo ini?')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('produk_id', currentPromoProductId);
        
        fetch('<?= base_url('produk/promo/delete') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showSnackbar('Promo Berhasil Dihapus!', 'success');
                closePromoModal();
                // Small delay to ensure snackbar is shown before refresh
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showSnackbar(data.message || 'Gagal Menghapus Promo', 'error');
            }
        })
        .catch(() => showSnackbar('Gagal Menghapus Promo', 'error'));
    }
    
    document.getElementById('promo_type').addEventListener('change', function() {
        const promoType = this.value;
        const promoValueInput = document.getElementById('promo_value');
        const wrapper = document.getElementById('promo_value_wrapper');
        
        if (promoType === 'percent') {
            wrapper.innerHTML = `
                <div class="promo-value-percentage">
                    <input type="text" 
                        id="promo_value" 
                        name="promo_value" 
                        class="form-input"
                        placeholder="Contoh: 10 untuk 10%"
                        required>
                </div>
            `;
        } else if (promoType === 'fixed') {
            wrapper.innerHTML = `
                <div class="currency-input-wrapper">
                    <span class="currency-prefix" style="transform: translateY(-50%)">Rp</span>
                    <input type="text" 
                        id="promo_value" 
                        name="promo_value" 
                        class="form-input currency-input promo-value-currency"
                        placeholder="0"
                        required>
                </div>
            `;
        } else {
            wrapper.innerHTML = `
                <input type="text" 
                    id="promo_value" 
                    name="promo_value" 
                    class="form-input"
                    placeholder="Masukkan nilai promo"
                    required>
            `;
        }
        
        attachPromoValueListeners();
        calculateFinalPrice();
    });
    
    function attachPromoValueListeners() {
        const promoValueInput = document.getElementById('promo_value');
        if (promoValueInput) {
            promoValueInput.addEventListener('input', function() {
                const promoType = document.getElementById('promo_type').value;
                
                if (promoType === 'fixed') {
                    let value = this.value.replace(/\D/g, '');
                    if (value) {
                        value = parseInt(value).toLocaleString('id-ID');
                    }
                    this.value = value;
                } else if (promoType === 'percent') {
                    let value = this.value.replace(/\D/g, '');
                    if (parseInt(value) > 100) {
                        value = '100';
                    }
                    this.value = value;
                }
                
                calculateFinalPrice();
            });
            
            promoValueInput.addEventListener('keypress', function(e) {
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105) && [8, 9, 27, 13, 46].indexOf(e.keyCode) === -1) {
                    e.preventDefault();
                }
            });
        }
    }
    
    function calculateFinalPrice() {
        const promoType = document.getElementById('promo_type').value;
        const promoValueInput = document.getElementById('promo_value');
        const finalPriceInput = document.getElementById('final_price');
        
        if (!promoType || !promoValueInput || !promoValueInput.value) {
            finalPriceInput.value = currentProductPrice.toLocaleString('id-ID');
            return;
        }
        
        let finalPrice = currentProductPrice;
        const promoValue = promoValueInput.value.replace(/\D/g, '');
        
        if (promoType === 'percent') {
            const discountPercent = parseInt(promoValue) || 0;
            const discountAmount = (currentProductPrice * discountPercent) / 100;
            finalPrice = currentProductPrice - discountAmount;
        } else if (promoType === 'fixed') {
            const discountAmount = parseInt(promoValue) || 0;
            finalPrice = currentProductPrice - discountAmount;
        }
        
        finalPrice = Math.max(0, finalPrice);
        
        finalPriceInput.value = finalPrice.toLocaleString('id-ID');
    }
    
    attachPromoValueListeners();
    
    document.getElementById('promoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('produk_id', document.getElementById('promo_produk_id').value);
        formData.append('promo_type', document.getElementById('promo_type').value);
        
        const promoValueRaw = document.getElementById('promo_value').value.replace(/\D/g, '');
        formData.append('promo_value', promoValueRaw);
        
        formData.append('promo_active', document.getElementById('promo_active').value);
        formData.append('promo_start', document.getElementById('promo_start').value);
        formData.append('promo_end', document.getElementById('promo_end').value);
        
        const finalPrice = document.getElementById('final_price').value.replace(/\D/g, '');
        formData.append('final_price', finalPrice);
        
        fetch('<?= base_url('produk/promo/store') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showSnackbar('Promo Berhasil Disimpan!', 'success');
                closePromoModal();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showSnackbar(data.message || 'Gagal Menyimpan Promo', 'error');
            }
        })
        .catch(() => showSnackbar('Gagal Menyimpan Promo', 'error'));
    });
    
    window.onclick = function(event) {
        const modal = document.getElementById('promoModal');
        if (event.target === modal) {
            closePromoModal();
        }
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePromoModal();
        }
    });
    
    function showSnackbar(message, type = 'success') {
        let snackbar = document.getElementById('snackbar');
        
        if (!snackbar) {
            snackbar = document.createElement('div');
            snackbar.id = 'snackbar';
            document.body.appendChild(snackbar);
        }
        
        snackbar.textContent = message;
        snackbar.className = `show ${type}`;
        
        setTimeout(() => {
            snackbar.className = snackbar.className.replace("show", "");
        }, 3000);
    }
</script>
