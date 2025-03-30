@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
                <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Ajax</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Filter Berdasarkan Kategori -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="Kategori_id" name="Kategori_id">
                                <option value="">- Semua -</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->Kategori_id }}">{{ $kat->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter berdasarkan kategori</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Harga Beli</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
        databackdrop="static"data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataUser;
        $(document).ready(function() {
            dataUser = $('#table_barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('barang/list') }}",
                    type: "POST",
                    dataType: "json",
                    // Kirim Kategori_id sebagai parameter untuk filter
                    data: function(d) {
                        d.Kategori_id = $('#Kategori_id').val();
                    }
                },
                columns: [{
                        data: "barang_id",
                        className: "text-center",
                        orderable: true
                    },
                    {
                        data: "barang_kode",
                        orderable: true
                    },
                    {
                        data: "barang_nama",
                        orderable: true
                    },
                    {
                        data: "kategori.kategori_nama",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "harga_jual",
                        orderable: true
                    },
                    {
                        data: "harga_beli",
                        orderable: true
                    },
                    {
                        data: "aksi",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            // Saat filter berubah, reload DataTables
            $('#Kategori_id').on('change', function() {
                dataUser.ajax.reload();
            });
        });
    </script>
@endpush
