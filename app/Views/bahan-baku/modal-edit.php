<?php include(APPPATH . 'Views/css/modal.php'); ?>

<div id="editBahanBakuModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Bahan Baku</h2>
            <span class="close" onclick="closeEditBahanBakuModal()">&times;</span>
        </div>
        
        <form id="editBahanBakuForm">
            <input type="hidden" id="edit_bahan_baku_id" name="bahan_baku_id">
            
            <div class="form-group">
                <label for="edit_nama_bahan" class="form-label">Nama Bahan Baku</label>
                <input type="text" 
                       id="edit_nama_bahan" 
                       name="nama_bahan" 
                       class="form-input"
                       placeholder="Masukkan Nama Bahan Baku"
                       required>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditBahanBakuModal()">Kembali</button>
                <button type="submit" class="btn-submit">Update Bahan Baku</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentEditBahanBakuId = null;
    
    function openEditBahanBakuModal(bahanBakuId, namaBahan) {
        currentEditBahanBakuId = bahanBakuId;
        
        // Set form values
        document.getElementById('edit_bahan_baku_id').value = bahanBakuId;
        document.getElementById('edit_nama_bahan').value = namaBahan;
        
        document.getElementById('editBahanBakuModal').style.display = 'block';
    }
    
    function closeEditBahanBakuModal() {
        document.getElementById('editBahanBakuModal').style.display = 'none';
        document.getElementById('editBahanBakuForm').reset();
        currentEditBahanBakuId = null;
    }
    
    document.getElementById('editBahanBakuForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('bahan_baku_id', document.getElementById('edit_bahan_baku_id').value);
        formData.append('nama_bahan', document.getElementById('edit_nama_bahan').value);
        
        fetch('<?= base_url('bahan-baku/update') ?>', {
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
                showSnackbar('Bahan Baku Berhasil Diupdate!', 'success');
                closeEditBahanBakuModal();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showSnackbar(data.message || 'Gagal Mengupdate Bahan Baku', 'error');
            }
        })
        .catch(() => showSnackbar('Gagal Mengupdate Bahan Baku', 'error'));
    });
    
    window.onclick = function(event) {
        const modal = document.getElementById('editBahanBakuModal');
        if (event.target === modal) {
            closeEditBahanBakuModal();
        }
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditBahanBakuModal();
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
