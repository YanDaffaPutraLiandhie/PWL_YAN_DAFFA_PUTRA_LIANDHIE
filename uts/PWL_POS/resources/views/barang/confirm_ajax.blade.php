@empty($barang)
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Kesalahan</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger mb-4">
          <i class="fas fa-ban"></i> Data yang Anda cari tidak ditemukan
        </div>
        <button class="btn btn-warning" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
@else
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{ url('/barang/' . $barang->barang_id . '/delete_ajax') }}"
          method="POST"
          id="form-delete">
      @csrf
      <input type="hidden" name="_method" value="DELETE">

      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Hapus Data Barang</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p class="text-danger"><i class="fas fa-exclamation-triangle"></i>
            Apakah Anda yakin ingin menghapus data berikut?</p>
          <table class="table table-sm table-bordered">
            <tr><th>Kategori</th><td>{{ $barang->kategori->kategori_nama }}</td></tr>
            <tr><th>Kode</th><td>{{ $barang->barang_kode }}</td></tr>
            <tr><th>Nama</th><td>{{ $barang->barang_nama }}</td></tr>
            <tr><th>Harga Beli</th><td>{{ $barang->harga_beli }}</td></tr>
            <tr><th>Harga Jual</th><td>{{ $barang->harga_jual }}</td></tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    $(function () {
      // Setup CSRF token secara global (optional tapi recommended)
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#form-delete').validate({
        submitHandler(form) {
          const formData = $(form).serializeArray();
          formData.push({ name: '_method', value: 'DELETE' }); // pastikan ini ditambahkan!

          $.ajax({
            url: form.action,
            type: 'POST',
            data: $.param(formData),
            success(resp) {
              $('#myModal').modal('hide');
              if (resp.status) {
                Swal.fire('Berhasil', resp.message, 'success');
                if (typeof dataBarang !== 'undefined') {
                  dataBarang.ajax.reload();
                }
              } else {
                Swal.fire('Gagal', resp.message, 'error');
              }
            },
            error(xhr) {
              Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
          });

          return false;
        }
      });
    });
  </script>
@endempty
