<!-- Modal Edit Stok -->
<div class="modal fade" id="modalEditStok" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditStok" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Stok - {{ $barang->barang_nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="barang_id" value="{{ $barang->barang_id }}">
                    <div class="form-group">
                        <label>Jumlah Stok (boleh negatif untuk pengurangan)</label>
                        <input type="number" name="stok_jumlah" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    $(document).ready(function () {
        // Menangani submit form
        $(document).on('submit', '#formEditStok', function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('stok.update') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        $('#modalEditStok').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        // Reload tabel atau halaman sesuai kebutuhan
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan.'
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memperbarui stok.'
                    });
                }
            });
        });
    });
</script>
