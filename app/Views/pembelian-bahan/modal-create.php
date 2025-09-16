<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="createModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Pembelian Bahan</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        
        <form id="createForm">
            <div class="form-group">
                <div class="date-name-row">
                    <div class="date-field">
                        <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                        <input type="date" 
                            id="tanggal_pembelian" 
                            name="tanggal_pembelian" 
                            class="form-input"
                            required>
                    </div>
                    
                    <div class="name-field">
                        <label for="nama_pembelian" class="form-label">Nama Pembelian</label>
                        <input type="text" 
                               id="nama_pembelian" 
                               name="nama_pembelian" 
                               class="form-input"
                               placeholder="Masukkan Nama Pembelian"
                               required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="source_money" class="form-label">Source Money</label>
                <select id="source_money" name="source_money" class="form-input" required>
                    <option value="">Pilih Source Money</option>
                    <option value="duit_pribadi">💳 Duit Pribadi</option>
                    <option value="bank_account">🏦 Bank Account</option>
                </select>
            </div>
            
            <hr style="margin: 20px 0;">

            <div id="item-list">
                <div class="item-card">
                    <div class="item-card-header">
                        <h3 class="item-card-title">Item #1</h3>
                    </div>
                    <div class="item-card-body">
                        <div class="item-fields">
                            <div class="form-group">
                                <label class="form-label">Nama Bahan Baku</label>
                                <select name="bahan_baku_id[]" class="form-input bahan-baku-select" required>
                                    <option value="">Pilih Bahan Baku</option>
                                    <?php if (isset($bahanBaku) && !empty($bahanBaku)): ?>
                                        <?php foreach ($bahanBaku as $bahan): ?>
                                            <option value="<?= $bahan['id'] ?>"><?= esc($bahan['nama_bahan']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Beli (Pack/Unit)</label>
                                <input type="number" name="jumlah_item[]" class="form-input" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Isi per Pack/Unit (pcs)</label>
                                <input type="number" name="isi_per_unit[]" class="form-input" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Harga per Pack/Unit</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" name="harga_item[]" class="form-input currency-input" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Harga per Pcs (Auto)</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" name="harga_per_unit[]" class="form-input" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="addItemBtn" class="create-btn" style="width: 100%; margin-bottom: 20px;">Tambah Item Lain</button>
            
            <div class="form-group">
                <div class="promo-row">
                    <div class="promo-field">
                        <label for="admin_fee" class="form-label">Biaya Admin</label>
                        <div class="currency-input-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" id="admin_fee" name="admin_fee" class="form-input currency-input" value="0">
                        </div>
                    </div>
                    <div class="promo-field">
                        <label for="discount" class="form-label">Diskon</label>
                        <div class="currency-input-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" id="discount" name="discount" class="form-input currency-input" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="final-price-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <label class="form-label" style="font-size: 1.2em;">Total Harga</label>
                        <div class="currency-input-wrapper" style="margin-top: 5px;">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" id="final_price" class="form-input currency-input final-price-input" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 1.2em;">Total Stok (pcs)</label>
                        <div style="margin-top: 5px;">
                            <input type="text" id="total_stock" class="form-input final-price-input" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Kembali</button>
                <button type="submit" class="btn-submit">Tambah Pembelian</button>
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
    
    .date-name-row {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }
    
    .date-field, .name-field {
        display: flex;
        flex-direction: column;
    }
    
    .date-field {
        flex: 1;
        min-width: 200px;
    }
    
    .name-field {
        flex: 2;
    }
    
    .date-field .form-label, .name-field .form-label {
        margin-bottom: 8px;
        height: 20px;
        line-height: 20px;
    }
    
    .date-field .form-input, .name-field .form-input {
        height: 40px;
    }
    
    .item-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
    }
    .item-card-header {
        background-color: <?= MAIN_COLOR; ?>;
        color: white;
        padding: 10px 15px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .item-card-header .item-card-title {
        margin: 0;
        font-size: 1.1em;
    }
    .item-card-header .remove-item-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.2em;
        cursor: pointer;
    }
    .item-card-body {
        padding: 15px;
    }
    .item-fields {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .item-fields .form-group {
        flex: 1;
        margin-bottom: 0;
        min-width: 150px;
    }
    .d-flex {
        display: flex;
        gap: 20px;
    }
    .justify-content-between {
        justify-content: space-between;
    }
    .align-items-center {
        align-items: center;
    }
    .currency-input {
        padding-left: 45px !important;
    }
    
    .form-input[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
        padding-left: 45px !important;
    }
    .promo-row {
        display: flex;
        gap: 15px;
    }
    .promo-field {
        flex: 1;
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
    });

    document.addEventListener('DOMContentLoaded', () => {
        let itemCounter = 1;
        const itemList = document.getElementById('item-list');
        const addItemBtn = document.getElementById('addItemBtn');
        const form = document.getElementById('createForm');
        
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
        
        function createItemCard() {
            itemCounter++;
            const newItemCard = document.createElement('div');
            newItemCard.className = 'item-card';
            newItemCard.innerHTML = `
                <div class="item-card-header">
                    <h3 class="item-card-title">Item #${itemCounter}</h3>
                    <button type="button" class="remove-item-btn">&times;</button>
                </div>
                <div class="item-card-body">
                    <div class="item-fields">
                        <div class="form-group">
                            <label class="form-label">Nama Bahan Baku</label>
                            <select name="bahan_baku_id[]" class="form-input bahan-baku-select" required>
                                <option value="">Pilih Bahan Baku</option>
                                <?php if (isset($bahanBaku) && !empty($bahanBaku)): ?>
                                    <?php foreach ($bahanBaku as $bahan): ?>
                                        <option value="<?= $bahan['id'] ?>"><?= esc($bahan['nama_bahan']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Beli (Pack/Unit)</label>
                            <input type="number" name="jumlah_item[]" class="form-input" min="1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Isi per Pack/Unit (pcs)</label>
                            <input type="number" name="isi_per_unit[]" class="form-input" min="1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga per Pack/Unit</label>
                            <div class="currency-input-wrapper">
                                <span class="currency-prefix">Rp</span>
                                <input type="text" name="harga_item[]" class="form-input currency-input" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga per Pcs (Auto)</label>
                            <div class="currency-input-wrapper">
                                <span class="currency-prefix">Rp</span>
                                <input type="text" name="harga_per_unit[]" class="form-input" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            itemList.appendChild(newItemCard);
            attachInputListeners(newItemCard);
        }

        function removeItemCard(itemCard) {
            if (itemList.children.length > 1) {
                itemCard.remove();
                reindexItems();
                calculateTotal();
            }
        }
        
        function reindexItems() {
            const items = itemList.querySelectorAll('.item-card');
            itemCounter = 0;
            items.forEach((item, index) => {
                itemCounter = index + 1;
                item.querySelector('.item-card-title').textContent = `Item #${itemCounter}`;
            });
        }

        function attachInputListeners(element) {
            const inputs = element.querySelectorAll('input[name="jumlah_item[]"], input[name="harga_item[]"], input[name="isi_per_unit[]"], #admin_fee, #discount');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (input.name === 'harga_item[]' || input.name === 'isi_per_unit[]' || input.name === 'jumlah_item[]') {
                        calculateUnitPrice(input);
                    }
                    calculateTotal();
                });
            });
            
            element.querySelectorAll('.currency-input').forEach(input => {
                input.addEventListener('input', formatCurrency);
            });

            const removeItemBtn = element.querySelector('.remove-item-btn');
            if (removeItemBtn) {
                removeItemBtn.addEventListener('click', () => removeItemCard(element));
            }
        }
        
        function formatCurrency(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            e.target.value = value;
        }

        function calculateUnitPrice(changedInput) {
            // This will be called from calculateTotal() to ensure admin fee and discount are included
            calculateTotal();
        }
        
        function updateAllUnitPrices() {
            const itemCards = itemList.querySelectorAll('.item-card');
            let subtotal = 0;
            
            // First, calculate subtotal to determine proportions
            itemCards.forEach(card => {
                const quantity = parseInt(card.querySelector('input[name="jumlah_item[]"]').value) || 0;
                const price = parseInt(card.querySelector('input[name="harga_item[]"]').value.replace(/\D/g, '')) || 0;
                subtotal += quantity * price;
            });
            
            const adminFee = parseInt(document.getElementById('admin_fee').value.replace(/\D/g, '')) || 0;
            const discount = parseInt(document.getElementById('discount').value.replace(/\D/g, '')) || 0;
            
            // Calculate adjustment factor
            const adjustment = adminFee - discount;
            
            // Update each item's unit price
            itemCards.forEach(card => {
                const quantity = parseInt(card.querySelector('input[name="jumlah_item[]"]').value) || 0;
                const isiPerUnit = parseInt(card.querySelector('input[name="isi_per_unit[]"]').value) || 1;
                const hargaItem = parseInt(card.querySelector('input[name="harga_item[]"]').value.replace(/\D/g, '')) || 0;
                const hargaPerUnitInput = card.querySelector('input[name="harga_per_unit[]"]');
                
                if (subtotal > 0 && isiPerUnit > 0) {
                    // Calculate this item's proportion of total cost
                    const itemTotal = quantity * hargaItem;
                    const proportion = itemTotal / subtotal;
                    
                    // Apply proportional adjustment
                    const itemAdjustment = adjustment * proportion;
                    const adjustedItemTotal = itemTotal + itemAdjustment;
                    
                    // Calculate final unit price
                    const totalPieces = quantity * isiPerUnit;
                    const finalUnitPrice = adjustedItemTotal / totalPieces;
                    
                    hargaPerUnitInput.value = Math.round(Math.max(0, finalUnitPrice)).toLocaleString('id-ID');
                } else {
                    hargaPerUnitInput.value = '0';
                }
            });
        }

        function calculateTotal() {
            const itemCards = itemList.querySelectorAll('.item-card');
            let subtotal = 0;
            let totalStock = 0;

            itemCards.forEach(card => {
                const quantity = parseInt(card.querySelector('input[name="jumlah_item[]"]').value) || 0;
                const isiPerUnit = parseInt(card.querySelector('input[name="isi_per_unit[]"]').value) || 1;
                const price = parseInt(card.querySelector('input[name="harga_item[]"]').value.replace(/\D/g, '')) || 0;
                
                subtotal += quantity * price;
                totalStock += quantity * isiPerUnit;
            });

            const adminFee = parseInt(document.getElementById('admin_fee').value.replace(/\D/g, '')) || 0;
            const discount = parseInt(document.getElementById('discount').value.replace(/\D/g, '')) || 0;
            
            let finalPrice = subtotal + adminFee - discount;
            finalPrice = Math.max(0, finalPrice);

            document.getElementById('final_price').value = finalPrice.toLocaleString('id-ID');
            document.getElementById('total_stock').value = totalStock + ' pcs';
            
            updateAllUnitPrices();
        }

        addItemBtn.addEventListener('click', createItemCard);
        
        attachInputListeners(itemList.querySelector('.item-card'));
        
        document.getElementById('admin_fee').addEventListener('input', function(e) {
            formatCurrency(e);
            calculateTotal();
        });
        document.getElementById('discount').addEventListener('input', function(e) {
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
                showSnackbar('Mohon lengkapi semua field yang wajib diisi: ' + missingFields.join(', '), 'error');
                console.log('Missing required fields:', missingFields);
                return;
            }
            
            const formData = new FormData(form);
            
            const adminFeeRaw = document.getElementById('admin_fee').value.replace(/\D/g, '');
            formData.set('admin_fee', adminFeeRaw);
            
            const discountRaw = document.getElementById('discount').value.replace(/\D/g, '');
            formData.set('discount', discountRaw);

            const itemPrices = document.querySelectorAll('input[name="harga_item[]"]');
            itemPrices.forEach((input, index) => {
                formData.set(`harga_item[${index}]`, input.value.replace(/\D/g, ''));
            });

            const hargaPerUnitInputs = document.querySelectorAll('input[name="harga_per_unit[]"]');
            hargaPerUnitInputs.forEach((input, index) => {
                formData.set(`harga_per_unit[${index}]`, input.value.replace(/\D/g, ''));
            });

            const finalPriceRaw = document.getElementById('final_price').value.replace(/\D/g, '');
            formData.append('harga_total', finalPriceRaw);
            
            console.log('Sending data to server...');
            
            fetch('<?= base_url('pembelian-bahan/store') ?>', {
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
                    showSnackbar('Pembelian Bahan Berhasil Ditambah!', 'success');
                    closeModal();
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showSnackbar(data.message || 'Gagal Menambahkan Pembelian Bahan', 'error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showSnackbar('Gagal Menambahkan Pembelian Bahan', 'error');
            });
        });
        
        // Fix close modal function - make it global
        window.closeModal = function() {
            document.getElementById('createModal').style.display = 'none';
            form.reset();
            itemList.innerHTML = `
                <div class="item-card">
                    <div class="item-card-header">
                        <h3 class="item-card-title">Item #1</h3>
                    </div>
                    <div class="item-card-body">
                        <div class="item-fields">
                            <div class="form-group">
                                <label class="form-label">Nama Bahan Baku</label>
                                <select name="bahan_baku_id[]" class="form-input bahan-baku-select" required>
                                    <option value="">Pilih Bahan Baku</option>
                                    <?php if (isset($bahanBaku) && !empty($bahanBaku)): ?>
                                        <?php foreach ($bahanBaku as $bahan): ?>
                                            <option value="<?= $bahan['id'] ?>"><?= esc($bahan['nama_bahan']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Beli (Pack/Unit)</label>
                                <input type="number" name="jumlah_item[]" class="form-input" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Isi per Pack/Unit (pcs)</label>
                                <input type="number" name="isi_per_unit[]" class="form-input" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Harga per Pack/Unit</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" name="harga_item[]" class="form-input currency-input" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Harga per Pcs (Auto)</label>
                                <div class="currency-input-wrapper">
                                    <span class="currency-prefix">Rp</span>
                                    <input type="text" name="harga_per_unit[]" class="form-input" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            itemCounter = 1;
            attachInputListeners(itemList.querySelector('.item-card'));
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