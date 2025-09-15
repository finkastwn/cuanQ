<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Produk</h2>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        
        <form id="editForm">
            <input type="hidden" id="edit_produk_id" name="produk_id">
            
            <div class="form-group">
                <label for="edit_nama_produk" class="form-label">Nama Produk</label>
                <input type="text" 
                       id="edit_nama_produk" 
                       name="nama_produk" 
                       class="form-input"
                       placeholder="Masukkan Nama Produk"
                       required>
                
                <label for="edit_harga_produk" class="form-label">Harga Produk</label>
                <div class="currency-input-wrapper">
                    <span class="currency-prefix">Rp</span>
                    <input type="text" 
                        id="edit_harga_produk" 
                        name="harga_produk" 
                        class="form-input currency-input"
                        placeholder="0"
                        required>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Kembali</button>
                <button type="submit" class="btn-submit">Update Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentEditProductId = null;
    
    function openEditModal(productId, productName, productPrice) {
        currentEditProductId = productId;
        
        // Set form values
        document.getElementById('edit_produk_id').value = productId;
        document.getElementById('edit_nama_produk').value = productName;
        document.getElementById('edit_harga_produk').value = parseInt(productPrice).toLocaleString('id-ID');
        
        document.getElementById('editModal').style.display = 'block';
    }
    
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('editForm').reset();
        currentEditProductId = null;
    }
    
    document.getElementById('edit_harga_produk').addEventListener('input', function(e) {
        let value = e.target.value;
        
        value = value.replace(/\D/g, '');
        
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        
        e.target.value = value;
    });
    
    document.getElementById('edit_harga_produk').addEventListener('keypress', function(e) {
        if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('produk_id', document.getElementById('edit_produk_id').value);
        formData.append('nama_produk', document.getElementById('edit_nama_produk').value);
        
        const hargaProduk = document.getElementById('edit_harga_produk').value.replace(/\./g, '');
        formData.append('harga_produk', hargaProduk);
        
        fetch('<?= base_url('produk/update') ?>', {
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
                showSnackbar('Produk Berhasil Diupdate!', 'success');
                closeEditModal();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showSnackbar(data.message || 'Gagal Mengupdate Produk', 'error');
            }
        })
        .catch(() => showSnackbar('Gagal Mengupdate Produk', 'error'));
    });
    
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditModal();
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
