<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="createModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Pesanan</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        
        <form id="createForm">
            <div class="form-group">
                <div class="customer-info-row">
                    <div class="customer-field">
                        <label for="nama_pembeli" class="form-label">Nama Pembeli</label>
                        <input type="text" 
                               id="nama_pembeli" 
                               name="nama_pembeli" 
                               class="form-input"
                               placeholder="Masukkan Nama Pembeli"
                               required>
                    </div>
                    
                    <div class="source-field">
                        <label for="source_penjualan" class="form-label">Source Penjualan</label>
                        <select id="source_penjualan" name="source_penjualan" class="form-input" required>
                            <option value="other">Other</option>
                            <option value="shopee">Shopee</option>
                            <option value="tiktok">TikTok</option>
                            <option value="facebook">Facebook</option>
                            <option value="twitter">Twitter</option>
                            <option value="instagram">Instagram</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="tanggal_pesanan" class="form-label">Tanggal Pesanan</label>
                <input type="date" 
                       id="tanggal_pesanan" 
                       name="tanggal_pesanan" 
                       class="form-input"
                       required>
            </div>
            
            <hr style="margin: 20px 0;">

            <div id="produk-list">
                <div class="produk-card">
                    <div class="produk-card-header">
                        <h3 class="produk-card-title">Produk #1</h3>
                    </div>
                    <div class="produk-card-body">
                        <div class="produk-fields">
                            <div class="form-group">
                                <label class="form-label">Nama Produk</label>
                                <select name="produk_id[]" class="form-input produk-select" required>
                                    <option value="">Pilih Produk</option>
                                    <?php if (isset($produk) && !empty($produk)): ?>
                                        <?php foreach ($produk as $p): ?>
                                            <?php 
                                            $hasPromo = !empty($p['promo_type']) && $p['promo_active'] == 1;
                                            $displayPrice = $hasPromo ? $p['harga_final'] : $p['harga_produk'];
                                            $originalPrice = $p['harga_produk'];
                                            ?>
                                            <option value="<?= $p['id'] ?>" 
                                                    data-harga="<?= $displayPrice ?>"
                                                    data-original-harga="<?= $originalPrice ?>"
                                                    data-has-promo="<?= $hasPromo ? 'true' : 'false' ?>"
                                                    data-promo-type="<?= $hasPromo ? $p['promo_type'] : '' ?>"
                                                    data-promo-value="<?= $hasPromo ? $p['promo_value'] : '' ?>"
                                                    data-nama="<?= esc($p['nama_produk']) ?>">
                                                <?= esc($p['nama_produk']) ?> - 
                                                <?php if ($hasPromo): ?>
                                                    🏷️ Rp <?= number_format($displayPrice, 0, ',', '.') ?>
                                                    <span style="text-decoration: line-through; color: #999; font-size: 0.9em;">
                                                        Rp <?= number_format($originalPrice, 0, ',', '.') ?>
                                                    </span>
                                                <?php else: ?>
                                                    Rp <?= number_format($displayPrice, 0, ',', '.') ?>
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Produk</label>
                                <input type="number" name="jumlah_produk[]" class="form-input produk-jumlah" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Harga Produk (Auto)</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" class="form-input produk-harga" readonly>
                                </div>
                            </div>
                            <div class="form-group biaya-admin-group" style="display: none;">
                                <label class="form-label">Biaya Admin (%)</label>
                                <input type="number" name="biaya_admin_persen[]" class="form-input admin-persen" 
                                       min="0" max="100" step="0.01" value="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Subtotal Item</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" class="form-input produk-subtotal" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="addProdukBtn" class="create-btn" style="width: 100%; margin-bottom: 20px;">Tambah Produk Lain</button>
            
            <div class="form-group">
                <div class="potongan-checkbox">
                    <input type="checkbox" id="ada_biaya_potongan" name="ada_biaya_potongan">
                    <label for="ada_biaya_potongan" class="checkbox-label">Ada biaya potongan?</label>
                </div>
            </div>
            
            <div id="biaya-potongan-section" style="display: none;">
                <div class="form-group">
                    <label for="biaya_pemrosesan" class="form-label">Biaya Pemrosesan</label>
                    <div class="currency-input-wrapper">
                        <span class="currency-prefix">Rp</span>
                        <input type="text" id="biaya_pemrosesan" name="biaya_pemrosesan" class="form-input currency-input" value="0">
                    </div>
                </div>
            </div>

            <div class="final-price-section">
                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Subtotal:</span>
                        <span id="display-subtotal">Rp 0</span>
                    </div>
                    <div class="price-row" id="admin-fee-row" style="display: none;">
                        <span>Total Biaya Admin:</span>
                        <span id="display-admin-fee">Rp 0</span>
                    </div>
                    <div class="price-row" id="processing-fee-row" style="display: none;">
                        <span>Biaya Pemrosesan:</span>
                        <span id="display-processing-fee">Rp 0</span>
                    </div>
                    <hr>
                    <div class="price-row total-row">
                        <span><strong>Total Harga:</strong></span>
                        <span id="display-total"><strong>Rp 0</strong></span>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Kembali</button>
                <button type="submit" class="btn-submit">Tambah Pesanan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .customer-info-row {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }
    
    .customer-field, .source-field {
        display: flex;
        flex-direction: column;
    }
    
    .customer-field {
        flex: 2;
    }
    
    .source-field {
        flex: 1;
        min-width: 200px;
    }
    
    .customer-field .form-label, .source-field .form-label {
        margin-bottom: 8px;
        height: 20px;
        line-height: 20px;
    }
    
    .customer-field .form-input, .source-field .form-input {
        height: 45px;
        line-height: 45px;
    }
    
    .produk-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
    }
    
    .produk-card-header {
        background-color: <?= MAIN_COLOR; ?>;
        color: white;
        padding: 10px 15px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .produk-card-header .produk-card-title {
        margin: 0;
        font-size: 1.1em;
    }
    
    .produk-card-header .remove-produk-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.2em;
        cursor: pointer;
    }
    
    .produk-card-body {
        padding: 15px;
    }
    
    .produk-fields {
        display: grid;
        grid-template-columns: 2fr 1fr 1.5fr 1fr 1.5fr;
        gap: 15px;
        align-items: end;
    }
    
    .produk-fields .form-group {
        margin-bottom: 0;
    }
    
    .potongan-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
    }
    
    .checkbox-label {
        font-weight: 600;
        cursor: pointer;
    }
    
    .price-breakdown {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .price-row:last-child {
        margin-bottom: 0;
    }
    
    .total-row {
        font-size: 1.2em;
        margin-top: 10px;
    }
    
    .currency-input {
        padding-left: 45px !important;
    }
    
    .form-input[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
        padding-left: 45px !important;
    }
    
    .promo-indicator {
        background-color: #28a745;
        color: white;
        font-size: 0.8em;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 8px;
        font-weight: 600;
    }
    
    .price-comparison {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.9em;
    }
    
    /* Snackbar styles */
    #snackbar {
        visibility: hidden;
        min-width: 250px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 8px;
        padding: 16px;
        position: fixed;
        z-index: 10000;
        left: 50%;
        top: 30px;
        font-size: 16px;
        transform: translateX(-50%);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    #snackbar.success {
        background-color: #4CAF50;
    }
    
    #snackbar.error {
        background-color: #f44336;
    }
    
    #snackbar.show {
        visibility: visible;
        animation: snackbar-fadein 0.5s, snackbar-fadeout 0.5s 2.5s;
    }
    
    @keyframes snackbar-fadein {
        from { top: 0; opacity: 0; }
        to { top: 30px; opacity: 1; }
    }
    
    @keyframes snackbar-fadeout {
        from { top: 30px; opacity: 1; }
        to { top: 0; opacity: 0; }
    }
</style>

<script>
    document.querySelector('.create-btn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('createModal').style.display = 'block';
        
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        
        document.getElementById('tanggal_pesanan').value = currentDateTime;
    });

    document.addEventListener('DOMContentLoaded', () => {
        let produkCounter = 1;
        const produkList = document.getElementById('produk-list');
        const addProdukBtn = document.getElementById('addProdukBtn');
        const form = document.getElementById('createForm');
        const biayaPotonganCheckbox = document.getElementById('ada_biaya_potongan');
        const biayaPotonganSection = document.getElementById('biaya-potongan-section');
        
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
                snackbar.className = snackbar.className.replace('show', '');
            }, 3000);
        }
        
        function createProdukCard() {
            produkCounter++;
            const newProdukCard = document.createElement('div');
            newProdukCard.className = 'produk-card';
            newProdukCard.innerHTML = `
                <div class="produk-card-header">
                    <h3 class="produk-card-title">Produk #${produkCounter}</h3>
                    <button type="button" class="remove-produk-btn">&times;</button>
                </div>
                <div class="produk-card-body">
                    <div class="produk-fields">
                        <div class="form-group">
                            <label class="form-label">Nama Produk</label>
                            <select name="produk_id[]" class="form-input produk-select" required>
                                <option value="">Pilih Produk</option>
                                <?php if (isset($produk) && !empty($produk)): ?>
                                    <?php foreach ($produk as $p): ?>
                                        <option value="<?= $p['id'] ?>" 
                                                data-harga="<?= $p['harga_final'] ?? $p['harga_produk'] ?>"
                                                data-nama="<?= esc($p['nama_produk']) ?>">
                                            <?= esc($p['nama_produk']) ?> - Rp <?= number_format($p['harga_final'] ?? $p['harga_produk'], 0, ',', '.') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Produk</label>
                            <input type="number" name="jumlah_produk[]" class="form-input produk-jumlah" min="1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga Produk (Auto)</label>
                            <div class="currency-input-wrapper">
                                <span class="currency-prefix">Rp</span>
                                <input type="text" class="form-input produk-harga" readonly>
                            </div>
                        </div>
                        <div class="form-group biaya-admin-group" style="display: none;">
                            <label class="form-label">Biaya Admin (%)</label>
                            <input type="number" name="biaya_admin_persen[]" class="form-input admin-persen" 
                                   min="0" max="100" step="0.01" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subtotal Item</label>
                            <div class="currency-input-wrapper">
                                <span class="currency-prefix">Rp</span>
                                <input type="text" class="form-input produk-subtotal" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            produkList.appendChild(newProdukCard);
            attachProdukListeners(newProdukCard);
        }

        function removeProdukCard(produkCard) {
            if (produkList.children.length > 1) {
                produkCard.remove();
                reindexProduk();
                calculateTotal();
            }
        }
        
        function reindexProduk() {
            const produkCards = produkList.querySelectorAll('.produk-card');
            produkCounter = 0;
            produkCards.forEach((card, index) => {
                produkCounter = index + 1;
                card.querySelector('.produk-card-title').textContent = `Produk #${produkCounter}`;
            });
        }

        function attachProdukListeners(element) {
            const produkSelect = element.querySelector('.produk-select');
            const jumlahInput = element.querySelector('.produk-jumlah');
            const hargaInput = element.querySelector('.produk-harga');
            const subtotalInput = element.querySelector('.produk-subtotal');
            const adminPersenInput = element.querySelector('.admin-persen');
            
            produkSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const harga = parseInt(selectedOption.dataset.harga) || 0;
                    const hasPromo = selectedOption.dataset.hasPromo === 'true';
                    const originalHarga = parseInt(selectedOption.dataset.originalHarga) || 0;
                    const promoType = selectedOption.dataset.promoType;
                    const promoValue = selectedOption.dataset.promoValue;
                    
                    if (hasPromo) {
                        hargaInput.value = harga.toLocaleString('id-ID');
                        const priceWrapper = hargaInput.parentElement;
                        let promoIndicator = priceWrapper.querySelector('.promo-indicator');
                        if (!promoIndicator) {
                            promoIndicator = document.createElement('span');
                            promoIndicator.className = 'promo-indicator';
                            priceWrapper.appendChild(promoIndicator);
                        }
                        
                        let promoText = '🏷️ PROMO';
                        if (promoType === 'percent') {
                            promoText += ` ${promoValue}%`;
                        } else if (promoType === 'fixed') {
                            promoText += ` -Rp${parseInt(promoValue).toLocaleString('id-ID')}`;
                        }
                        promoIndicator.textContent = promoText;
                        
                        let originalPriceSpan = priceWrapper.querySelector('.original-price');
                        if (!originalPriceSpan) {
                            originalPriceSpan = document.createElement('span');
                            originalPriceSpan.className = 'original-price';
                            priceWrapper.appendChild(originalPriceSpan);
                        }
                        originalPriceSpan.textContent = `Rp ${originalHarga.toLocaleString('id-ID')}`;
                    } else {
                        hargaInput.value = harga.toLocaleString('id-ID');
                        const priceWrapper = hargaInput.parentElement;
                        const promoIndicator = priceWrapper.querySelector('.promo-indicator');
                        const originalPriceSpan = priceWrapper.querySelector('.original-price');
                        if (promoIndicator) promoIndicator.remove();
                        if (originalPriceSpan) originalPriceSpan.remove();
                    }
                    
                    calculateProdukSubtotal(element);
                } else {
                    hargaInput.value = '';
                    subtotalInput.value = '';
                    const priceWrapper = hargaInput.parentElement;
                    const promoIndicator = priceWrapper.querySelector('.promo-indicator');
                    const originalPriceSpan = priceWrapper.querySelector('.original-price');
                    if (promoIndicator) promoIndicator.remove();
                    if (originalPriceSpan) originalPriceSpan.remove();
                }
                calculateTotal();
            });
            
            jumlahInput.addEventListener('input', function() {
                calculateProdukSubtotal(element);
                calculateTotal();
            });
            
            adminPersenInput.addEventListener('input', function() {
                calculateTotal();
            });
            
            const removeBtn = element.querySelector('.remove-produk-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', () => removeProdukCard(element));
            }
        }
        
        function calculateProdukSubtotal(produkCard) {
            const hargaInput = produkCard.querySelector('.produk-harga');
            const jumlahInput = produkCard.querySelector('.produk-jumlah');
            const subtotalInput = produkCard.querySelector('.produk-subtotal');
            
            const harga = parseInt(hargaInput.value.replace(/\D/g, '')) || 0;
            const jumlah = parseInt(jumlahInput.value) || 0;
            const subtotal = harga * jumlah;
            
            subtotalInput.value = subtotal.toLocaleString('id-ID');
        }
        
        function calculateTotal() {
            const produkCards = produkList.querySelectorAll('.produk-card');
            let subtotal = 0;
            let totalBiayaAdmin = 0;
            
            produkCards.forEach(card => {
                const subtotalInput = card.querySelector('.produk-subtotal');
                const adminPersenInput = card.querySelector('.admin-persen');
                
                const produkSubtotal = parseInt(subtotalInput.value.replace(/\D/g, '')) || 0;
                subtotal += produkSubtotal;
                
                if (biayaPotonganCheckbox.checked) {
                    const adminPersen = parseFloat(adminPersenInput.value) || 0;
                    const biayaAdmin = (produkSubtotal * adminPersen) / 100;
                    totalBiayaAdmin += biayaAdmin;
                }
            });
            
            const biayaPemrosesan = biayaPotonganCheckbox.checked ? 
                (parseInt(document.getElementById('biaya_pemrosesan').value.replace(/\D/g, '')) || 0) : 0;
            
            const totalHarga = subtotal - totalBiayaAdmin - biayaPemrosesan;
            
            document.getElementById('display-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('display-admin-fee').textContent = 'Rp ' + Math.round(totalBiayaAdmin).toLocaleString('id-ID');
            document.getElementById('display-processing-fee').textContent = 'Rp ' + biayaPemrosesan.toLocaleString('id-ID');
            document.getElementById('display-total').innerHTML = '<strong>Rp ' + Math.max(0, totalHarga).toLocaleString('id-ID') + '</strong>';
        }
        
        function formatCurrency(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            e.target.value = value;
        }
        
        biayaPotonganCheckbox.addEventListener('change', function() {
            if (this.checked) {
                biayaPotonganSection.style.display = 'block';
                document.querySelectorAll('.biaya-admin-group').forEach(el => el.style.display = 'block');
                document.getElementById('admin-fee-row').style.display = 'flex';
                document.getElementById('processing-fee-row').style.display = 'flex';
            } else {
                biayaPotonganSection.style.display = 'none';
                document.querySelectorAll('.biaya-admin-group').forEach(el => el.style.display = 'none');
                document.getElementById('admin-fee-row').style.display = 'none';
                document.getElementById('processing-fee-row').style.display = 'none';
                document.getElementById('biaya_pemrosesan').value = '0';
                document.querySelectorAll('.admin-persen').forEach(input => input.value = '0');
            }
            calculateTotal();
        });

        addProdukBtn.addEventListener('click', createProdukCard);
        
        attachProdukListeners(produkList.querySelector('.produk-card'));
        
        document.getElementById('biaya_pemrosesan').addEventListener('input', function(e) {
            formatCurrency(e);
            calculateTotal();
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submission started');
            
            const requiredFields = form.querySelectorAll('[required]');
            let missingFields = [];
            requiredFields.forEach(field => {
                if (!field.value || field.value.trim() === '') {
                    missingFields.push(field.name || field.id);
                }
            });
            
            if (missingFields.length > 0) {
                showSnackbar('Mohon lengkapi semua field yang wajib diisi', 'error');
                console.log('Missing required fields:', missingFields);
                return;
            }
            
            const formData = new FormData(form);
            
            const biayaPemrosesanRaw = document.getElementById('biaya_pemrosesan').value.replace(/\D/g, '');
            formData.set('biaya_pemrosesan', biayaPemrosesanRaw);
            
            console.log('Sending data to server...');
            
            fetch('<?= base_url('pesanan/store') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                body: formData
            })
            .then(res => {
                console.log('Response received:', res);
                return res.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.status === 'success') {
                    showSnackbar('Pesanan Berhasil Ditambah!', 'success');
                    closeModal();
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showSnackbar(data.message || 'Gagal Menambahkan Pesanan', 'error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showSnackbar('Gagal Menambahkan Pesanan', 'error');
            });
        });
        
        window.closeModal = function() {
            document.getElementById('createModal').style.display = 'none';
            form.reset();
            
            produkList.innerHTML = `
                <div class="produk-card">
                    <div class="produk-card-header">
                        <h3 class="produk-card-title">Produk #1</h3>
                    </div>
                    <div class="produk-card-body">
                        <div class="produk-fields">
                            <div class="form-group">
                                <label class="form-label">Nama Produk</label>
                                <select name="produk_id[]" class="form-input produk-select" required>
                                    <option value="">Pilih Produk</option>
                                    <?php if (isset($produk) && !empty($produk)): ?>
                                        <?php foreach ($produk as $p): ?>
                                            <?php 
                                            $hasPromo = !empty($p['promo_type']) && $p['promo_active'] == 1;
                                            $displayPrice = $hasPromo ? $p['harga_final'] : $p['harga_produk'];
                                            $originalPrice = $p['harga_produk'];
                                            ?>
                                            <option value="<?= $p['id'] ?>" 
                                                    data-harga="<?= $displayPrice ?>"
                                                    data-original-harga="<?= $originalPrice ?>"
                                                    data-has-promo="<?= $hasPromo ? 'true' : 'false' ?>"
                                                    data-promo-type="<?= $hasPromo ? $p['promo_type'] : '' ?>"
                                                    data-promo-value="<?= $hasPromo ? $p['promo_value'] : '' ?>"
                                                    data-nama="<?= esc($p['nama_produk']) ?>">
                                                <?= esc($p['nama_produk']) ?> - 
                                                <?php if ($hasPromo): ?>
                                                    🏷️ Rp <?= number_format($displayPrice, 0, ',', '.') ?>
                                                    <span style="text-decoration: line-through; color: #999; font-size: 0.9em;">
                                                        Rp <?= number_format($originalPrice, 0, ',', '.') ?>
                                                    </span>
                                                <?php else: ?>
                                                    Rp <?= number_format($displayPrice, 0, ',', '.') ?>
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Produk</label>
                                <input type="number" name="jumlah_produk[]" class="form-input produk-jumlah" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Harga Produk (Auto)</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" class="form-input produk-harga" readonly>
                                </div>
                            </div>
                            <div class="form-group biaya-admin-group" style="display: none;">
                                <label class="form-label">Biaya Admin (%)</label>
                                <input type="number" name="biaya_admin_persen[]" class="form-input admin-persen" 
                                       min="0" max="100" step="0.01" value="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Subtotal Item</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" class="form-input produk-subtotal" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            produkCounter = 1;
            attachProdukListeners(produkList.querySelector('.produk-card'));
            
            biayaPotonganCheckbox.checked = false;
            biayaPotonganSection.style.display = 'none';
            document.querySelectorAll('.biaya-admin-group').forEach(el => el.style.display = 'none');
            document.getElementById('admin-fee-row').style.display = 'none';
            document.getElementById('processing-fee-row').style.display = 'none';
            
            calculateTotal();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('createModal');
            if (event.target === modal) {
                closeModal();
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        calculateTotal();
    });
</script>
