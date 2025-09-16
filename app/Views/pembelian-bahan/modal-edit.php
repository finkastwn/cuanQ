<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="editPembelianModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Edit Pembelian Bahan</h2>
            <span class="close" onclick="closeEditPembelianModal()">&times;</span>
        </div>
        
        <form id="editPembelianForm">
            <input type="hidden" id="edit_pembelian_id" name="pembelian_id">
            
            <div class="form-group">
                <div class="date-name-row">
                    <div class="date-field">
                        <label for="edit_tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                        <input type="date" 
                            id="edit_tanggal_pembelian" 
                            name="tanggal_pembelian" 
                            class="form-input"
                            required>
                    </div>
                    
                    <div class="name-field">
                        <label for="edit_nama_pembelian" class="form-label">Nama Pembelian</label>
                        <input type="text" 
                               id="edit_nama_pembelian" 
                               name="nama_pembelian" 
                               class="form-input"
                               placeholder="Masukkan Nama Pembelian"
                               required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit_source_money" class="form-label">Source Money</label>
                <select id="edit_source_money" name="source_money" class="form-input" required>
                    <option value="">Pilih Source Money</option>
                    <option value="duit_pribadi">💳 Duit Pribadi</option>
                    <option value="bank_account">🏦 Bank Account</option>
                </select>
            </div>
            
            <div class="form-group">
                <div class="promo-row">
                    <div class="promo-field">
                        <label for="edit_admin_fee" class="form-label">Biaya Admin</label>
                        <div class="currency-input-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" id="edit_admin_fee" name="admin_fee" class="form-input currency-input" value="0">
                        </div>
                    </div>
                    <div class="promo-field">
                        <label for="edit_discount" class="form-label">Diskon</label>
                        <div class="currency-input-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="text" id="edit_discount" name="discount" class="form-input currency-input" value="0">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditPembelianModal()">Kembali</button>
                <button type="submit" class="btn-submit">Update Pembelian</button>
            </div>
        </form>
    </div>
</div>

<style>
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
    
    .promo-row {
        display: flex;
        gap: 15px;
    }
    
    .promo-field {
        flex: 1;
    }
    
    .currency-input {
        padding-left: 45px !important;
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
    function openEditPembelianModal(id, nama, tanggal, sourceMoney, adminFee, discount) {
        document.getElementById('edit_pembelian_id').value = id;
        document.getElementById('edit_nama_pembelian').value = nama;
        document.getElementById('edit_tanggal_pembelian').value = tanggal;
        document.getElementById('edit_source_money').value = sourceMoney;
        document.getElementById('edit_admin_fee').value = adminFee.toLocaleString('id-ID');
        document.getElementById('edit_discount').value = discount.toLocaleString('id-ID');
        
        document.getElementById('editPembelianModal').style.display = 'block';
    }
    
    function closeEditPembelianModal() {
        document.getElementById('editPembelianModal').style.display = 'none';
        document.getElementById('editPembelianForm').reset();
    }
    
    function formatCurrency(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        e.target.value = value;
    }
    
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
    
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('edit_admin_fee').addEventListener('input', formatCurrency);
        document.getElementById('edit_discount').addEventListener('input', formatCurrency);
        
        document.getElementById('editPembelianForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            const adminFeeRaw = document.getElementById('edit_admin_fee').value.replace(/\D/g, '');
            formData.set('admin_fee', adminFeeRaw);
            
            const discountRaw = document.getElementById('edit_discount').value.replace(/\D/g, '');
            formData.set('discount', discountRaw);
            
            fetch('<?= base_url('pembelian-bahan/update') ?>', {
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
                    showSnackbar('Pembelian Bahan Berhasil Diupdate!', 'success');
                    closeEditPembelianModal();
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showSnackbar(data.message || 'Gagal Mengupdate Pembelian Bahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSnackbar('Gagal Mengupdate Pembelian Bahan', 'error');
            });
        });
        
        window.onclick = function(event) {
            const modal = document.getElementById('editPembelianModal');
            if (event.target === modal) {
                closeEditPembelianModal();
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditPembelianModal();
            }
        });
    });
</script>
