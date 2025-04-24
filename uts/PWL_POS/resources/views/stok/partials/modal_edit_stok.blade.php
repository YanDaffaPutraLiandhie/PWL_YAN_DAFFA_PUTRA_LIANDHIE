<div class="modal-dialog">
    <div class="modal-content">
        <form id="formEditStok" method="POST" action="{{ route('stok.update') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Edit Stok — {{ $barang->barang_nama }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                  <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="barang_id" value="{{ $barang->barang_id }}">
                <div class="form-group">
                    <label>Jumlah Stok (positif/tambah, negatif/kurang)</label>
                    <input type="number" name="stok_jumlah" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).on('submit','#formEditStok',function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(res){
            if(res.success){
                $('#myModal').modal('hide');
                Swal.fire('Sukses',res.message,'success');
                location.reload();
            }
        },
        error: function(){
            Swal.fire('Error','Gagal memperbarui stok','error');
        }
    });
});
</script>
