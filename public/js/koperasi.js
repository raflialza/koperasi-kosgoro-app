// public/js/koperasi.js

document.addEventListener('DOMContentLoaded', function () {
    
    // ... (kode tooltip yang sudah ada)

    /**
     * Logika untuk pembayaran angsuran massal.
     */
    const formBayarMassal = document.getElementById('form-bayar-massal');
    if (formBayarMassal) {
        const pilihSemuaCheckbox = document.getElementById('pilih-semua');
        const pilihAngsuranCheckboxes = document.querySelectorAll('.pilih-angsuran');
        const tombolBayarMassal = document.getElementById('tombol-bayar-massal');

        function toggleTombolBayar() {
            const adaYangDipilih = Array.from(pilihAngsuranCheckboxes).some(cb => cb.checked);
            tombolBayarMassal.disabled = !adaYangDipilih;
        }

        pilihSemuaCheckbox.addEventListener('change', function () {
            pilihAngsuranCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleTombolBayar();
        });

        pilihAngsuranCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                toggleTombolBayar();
            });
        });

        // Inisialisasi awal
        toggleTombolBayar();
    }
});