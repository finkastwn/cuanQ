<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Pembelian Bahan</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        
        <form id="createForm">
            <div class="form-group">
                <div class="date-field">
                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                    <input type="date" 
                        id="tanggal_pembelian" 
                        name="tanggal_pembelian" 
                        class="form-input"
                        required>
                </div>
                
                <label for="nama_pembelian" class="form-label">Nama Pembelian</label>
                    <input type="text" 
                       id="nama_pembelian" 
                       name="nama_pembelian" 
                       class="form-input"
                       placeholder="Masukkan Nama Pembelian"
                       required>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Kembali</button>
                <button type="submit" class="btn-submit">Tambah Pembelian</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('.create-btn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('createModal').style.display = 'block';
    });
    
    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('nama_pembelian', document.getElementById('nama_pembelian').value);
        formData.append('tanggal_pembelian', document.getElementById('tanggal_pembelian').value);
        
        fetch('<?= base_url('pembelian-bahan/store') ?>', {
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
                
                showSnackbar('Pembelian Bahan Berhasil Ditambah!', 'success');
                closeModal();
            } else {
                showSnackbar(data.message || 'Gagal Menambahkan Pembelian Bahan', 'error');
            }
        })
        .catch(() => showSnackbar('Gagal Menambahkan Pembelian Bahan', 'error'));
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
