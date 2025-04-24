    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="editPenjualanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="editPenjualanForm">
                        @csrf
                        <input type="hidden" name="penjualan_id" value="{{ $penjualan->penjualan_id }}">

                        <div class="form-group">
                            <label for="pembeli">Nama Pembeli</label>
                            <input type="text" class="form-control" name="pembeli" id="pembeli" value="{{ $penjualan->pembeli }}" required>
                        </div>

                        <div id="barangList">
                            @foreach($penjualan_detail as $detail)
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label>Barang</label>
                                    <select class="form-control" name="barang[{{ $loop->index }}][id]" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($barang as $b)
                                            <option value="{{ $b['barang_id'] }}" {{ $b['barang_id'] == $detail['barang_id'] ? 'selected' : '' }}>
                                                {{ $b['barang_nama'] }} (Stok: {{ $b['stok_available'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Jumlah</label>
                                    <input type="number" class="form-control" name="barang[{{ $loop->index }}][quantity]" value="{{ $detail['jumlah'] }}" min="1" required>
                                </div>
                                <div class="col-auto align-self-end">
                                    <button type="button" class="btn btn-danger remove-barang">Hapus</button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-secondary mt-2" id="addBarang">Tambah Barang</button>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="saveEditPenjualan">Simpan Perubahan</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Tambah input barang
            $('#addBarang').click(function () {
                const index = $('#barangList .form-row').length;
    
                let barangOptions = `
                    <option value="">Pilih Barang</option>
                    @foreach($barang as $b)
                        <option value="{{ $b['barang_id'] }}">{{ $b['barang_nama'] }} (Stok: {{ $b['stok_available'] }})</option>
                    @endforeach
                `;
    
                let html = `
                    <div class="form-row mb-2">
                        <div class="col">
                            <label>Barang</label>
                            <select class="form-control" name="barang[${index}][id]" required>
                                ${barangOptions}
                            </select>
                        </div>
                        <div class="col">
                            <label>Jumlah</label>
                            <input type="number" class="form-control" name="barang[${index}][quantity]" min="1" required>
                        </div>
                        <div class="col-auto align-self-end">
                            <button type="button" class="btn btn-danger remove-barang">Hapus</button>
                        </div>
                    </div>
                `;
    
                $('#barangList').append(html);
            });
    
            // Hapus input barang
            $(document).on('click', '.remove-barang', function () {
                $(this).closest('.form-row').remove();
            });
    
            // Submit form edit
            $('#saveEditPenjualan').click(function () {
                $.ajax({
                    url: '{{ url("/penjualan/update") }}',
                    type: 'POST',
                    data: $('#editPenjualanForm').serialize(),
                    success: function (response) {
                        if (response.status) {
                            alert(response.message);
                            $('#myModal').modal('hide');
                            // reload table kalau ada DataTables
                            if (typeof dataPenjualan !== 'undefined') {
                                dataPenjualan.ajax.reload();
                            }
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat menyimpan data.');
                    }
                });
            });
        });
    </script>
    