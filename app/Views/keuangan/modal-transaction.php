<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="transactionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modal-title">Tambah Transaksi Manual</h2>
            <span class="close" onclick="closeTransactionModal()">&times;</span>
        </div>
        
        <form id="transactionForm">
            <input type="hidden" id="transaction_id" name="transaction_id">
            
            <div class="form-group">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" 
                       id="tanggal" 
                       name="tanggal" 
                       class="form-input"
                       required>
            </div>
            
            <div class="form-group">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" 
                       id="keterangan" 
                       name="keterangan" 
                       class="form-input"
                       placeholder="Masukkan keterangan transaksi"
                       required>
            </div>

            <div class="form-group">
                <label for="kategori_manual" class="form-label">Kategori</label>
                <select id="kategori_manual" name="kategori" class="form-input">
                    <option value="manual">Umum / Manual</option>
                    <option value="penyesuaian_saldo">Penyesuaian Saldo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="type" class="form-label">Tipe Transaksi</label>
                <select id="type" name="type" class="form-input" required>
                    <option value="">Pilih Tipe</option>
                    <option value="pemasukan">📈 Pemasukan</option>
                    <option value="pengeluaran">📉 Pengeluaran</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="source_money" class="form-label">Source Money</label>
                <select id="source_money" name="source_money" class="form-input" required>
                    <option value="">Pilih Source Money</option>
                    <option value="bank_account">🏦 Bank Account</option>
                    <option value="duit_pribadi">💳 Duit Pribadi</option>
                    <option value="shopee_pocket">🛒 Shopee Pocket</option>
                </select>
            </div>
            
            <div class="form-group" id="utang_category_group" style="display: none;">
                <label for="utang_category" class="form-label">Kategori Utang</label>
                <select id="utang_category" name="utang_category" class="form-input">
                    <option value="">Pilih Kategori</option>
                    <option value="manual_utang">➕ Tambah Utang Manual</option>
                    <option value="pembayaran_utang">➖ Pembayaran Utang</option>
                </select>
            </div>
            
            <div class="form-group" id="budget_source_group" style="display: none;">
                <label for="budget_source" class="form-label">Kurangi dari Budget</label>
                <select id="budget_source" name="budget_source" class="form-input">
                    <option value="">Tidak mengurangi budget</option>
                    <option value="hpp_bahan">📦 HPP Bahan</option>
                    <option value="hpp_jasa">🖨️ HPP Jasa</option>
                    <option value="keuntungan">💰 Keuntungan</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="jumlah" class="form-label">Jumlah</label>
                <div class="currency-input-wrapper">
                    <span class="currency-prefix">Rp</span>
                    <input type="text" 
                           id="jumlah" 
                           name="jumlah" 
                           class="form-input currency-input"
                           placeholder="0"
                           required>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeTransactionModal()">Kembali</button>
                <button type="submit" class="btn-submit" id="submit-btn">Tambah Transaksi</button>
            </div>
        </form>
    </div>
</div>

<style>
    .currency-input {
        padding-left: 45px !important;
    }
    
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
    let isEditMode = false;

    function openTransactionModal() {
        isEditMode = false;
        document.getElementById('transactionModal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Tambah Transaksi Manual';
        document.getElementById('submit-btn').textContent = 'Tambah Transaksi';
        document.getElementById('transactionForm').reset();
        document.getElementById('transaction_id').value = '';
        document.getElementById('budget_source_group').style.display = 'none';
        document.getElementById('utang_category_group').style.display = 'none';
            document.getElementById('kategori_manual').value = 'manual';
        
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').value = today;
    }

    function editTransaction(id, tanggal, keterangan, type, sourceMoney, jumlah, budgetSource, kategori) {
        isEditMode = true;
        document.getElementById('transactionModal').style.display = 'block';
        document.getElementById('modal-title').textContent = 'Edit Transaksi Manual';
        document.getElementById('submit-btn').textContent = 'Update Transaksi';
        
        document.getElementById('transaction_id').value = id;
        document.getElementById('tanggal').value = tanggal;
        document.getElementById('keterangan').value = keterangan;
        document.getElementById('type').value = type;
        document.getElementById('source_money').value = sourceMoney || 'bank_account';
        document.getElementById('jumlah').value = jumlah.toLocaleString('id-ID');
        document.getElementById('budget_source').value = budgetSource || '';
        
        const utangCategorySelect = document.getElementById('utang_category');
        const kategoriManualSelect = document.getElementById('kategori_manual');
        if (kategori === 'manual_utang' || kategori === 'pembayaran_utang') {
            utangCategorySelect.value = kategori;
            kategoriManualSelect.value = 'manual';
        } else {
            utangCategorySelect.value = '';
            kategoriManualSelect.value = kategori || 'manual';
        }
        
        updateUtangAndBudgetFields();
    }

    function closeTransactionModal() {
        document.getElementById('transactionModal').style.display = 'none';
        document.getElementById('transactionForm').reset();
        isEditMode = false;
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
        document.getElementById('jumlah').addEventListener('input', formatCurrency);
        
        document.getElementById('source_money').addEventListener('change', function() {
            updateUtangAndBudgetFields();
        });
        
        document.getElementById('type').addEventListener('change', function() {
            updateUtangAndBudgetFields();
        });
        
        function updateUtangAndBudgetFields() {
            const typeSelect = document.getElementById('type');
            const sourceMoneySelect = document.getElementById('source_money');
            const budgetSourceGroup = document.getElementById('budget_source_group');
            const utangCategoryGroup = document.getElementById('utang_category_group');
            const kategoriManualSelect = document.getElementById('kategori_manual');
            
            if (typeSelect.value === 'pengeluaran') {
                if (sourceMoneySelect.value === 'duit_pribadi') {
                    utangCategoryGroup.style.display = 'block';
                    budgetSourceGroup.style.display = 'none';
                    document.getElementById('budget_source').value = '';
                } else if (sourceMoneySelect.value === 'bank_account') {
                    utangCategoryGroup.style.display = 'block';
                    budgetSourceGroup.style.display = 'block';
                } else {
                    budgetSourceGroup.style.display = 'block';
                    utangCategoryGroup.style.display = 'none';
                    document.getElementById('utang_category').value = '';
                }
            } else {
                budgetSourceGroup.style.display = 'none';
                utangCategoryGroup.style.display = 'none';
                document.getElementById('budget_source').value = '';
                document.getElementById('utang_category').value = '';
            }

            // Jika kategori penyesuaian_saldo dipilih, jangan pakai budget/utang
            if (kategoriManualSelect.value === 'penyesuaian_saldo') {
                budgetSourceGroup.style.display = 'none';
                utangCategoryGroup.style.display = 'none';
                document.getElementById('budget_source').value = '';
                document.getElementById('utang_category').value = '';
            }
        }
        
        document.getElementById('transactionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const jumlahRaw = document.getElementById('jumlah').value.replace(/\D/g, '');
            formData.set('jumlah', jumlahRaw);
            
            const utangCategory = document.getElementById('utang_category').value;
            if (utangCategory) {
                formData.set('kategori', utangCategory);
            } else {
                formData.set('kategori', document.getElementById('kategori_manual').value || 'manual');
            }
            
            if (isEditMode) {
                formData.append('id', document.getElementById('transaction_id').value);
            }
            
            const url = isEditMode ? '<?= base_url('keuangan/update') ?>' : '<?= base_url('keuangan/store') ?>';
            
            fetch(url, {
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
                    showSnackbar(data.message, 'success');
                    closeTransactionModal();
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showSnackbar(data.message || 'Gagal menyimpan transaksi', 'error');
                }
            })
            .catch(() => showSnackbar('Gagal menyimpan transaksi', 'error'));
        });

        window.onclick = function(event) {
            const modal = document.getElementById('transactionModal');
            if (event.target === modal) {
                closeTransactionModal();
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeTransactionModal();
            }
        });
    });
</script>
