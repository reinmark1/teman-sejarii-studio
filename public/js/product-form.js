document.addEventListener('DOMContentLoaded', () => {
    // 1. Live Image Preview pada Form Create & Edit
    const imageInput = document.querySelector('[data-image-input]');
    const previewWrapper = document.getElementById('preview-wrapper');
    const previewImg = document.getElementById('preview-img');

    if (imageInput && previewWrapper && previewImg) {
        imageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) return;

            previewImg.src = URL.createObjectURL(file);
            previewWrapper.classList.remove('hidden');
        });
    }

    // 2. Konfirmasi Hapus Produk pada Halaman Edit
    const deleteBtn = document.querySelector('[data-delete-trigger]');
    const deleteForm = document.getElementById('delete-form');

    if (deleteBtn && deleteForm) {
        deleteBtn.addEventListener('click', () => {
            if (confirm('Hapus permanen koleksi ini beserta seluruh riwayat transaksinya?')) {
                deleteForm.submit();
            }
        });
    }
});