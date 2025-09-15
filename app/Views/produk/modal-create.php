<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Produk</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        
        <form id="createForm">
            <div class="form-group">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" 
                       id="nama_produk" 
                       name="nama_produk" 
                       class="form-input"
                       placeholder="Masukkan Nama Produk"
                       required>
                
                <label for="harga_produk" class="form-label">Harga Produk</label>
                <div class="currency-input-wrapper">
                    <span class="currency-prefix">Rp</span>
                    <input type="text" 
                        id="harga_produk" 
                        name="harga_produk" 
                        class="form-input currency-input"
                        placeholder="0"
                        required>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Kembali</button>
                <button type="submit" class="btn-submit">Tambah Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('.create-btn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('createModal').style.display = 'block';
    });
    
    document.getElementById('harga_produk').addEventListener('input', function(e) {
        let value = e.target.value;
        
        value = value.replace(/\D/g, '');
        
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        
        e.target.value = value;
    });
    
    document.getElementById('harga_produk').addEventListener('keypress', function(e) {
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
    
    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('nama_produk', document.getElementById('nama_produk').value);
        
        const hargaProduk = document.getElementById('harga_produk').value.replace(/\./g, '');
        formData.append('harga_produk', hargaProduk);
        
        fetch('<?= base_url('produk/store') ?>', {
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
                const tbody = document.querySelector('.table tbody');
                const noDataRow = tbody.querySelector('.no-data-row');
                
                setTimeout(() => {
                    location.reload();
                }, 500);
                
                showSnackbar('Produk Berhasil Ditambah!', 'success');
                closeModal();
            } else {
                showSnackbar(data.message || 'Gagal Menambahkan Produk', 'error');
            }
        })
        .catch(() => showSnackbar('Gagal Menambahkan Produk', 'error'));
    });
    
    function closeModal() {
        document.getElementById('createModal').style.display = 'none';
        document.getElementById('createForm').reset();
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
