@extends('layouts.template')

@section('title','Data Stok')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Data Stok</h3>
        <div class="card-tools">
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-sm" id="table_stok">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Total Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stok as $item)
                <tr>
                    <td>{{ $item->barang_id }}</td>
                    <td>{{ $item->barang_kode }}</td>
                    <td>{{ $item->barang_nama }}</td>
                    <td>
                        {{ $item->total_stok }}
                        <button
                            class="btn btn-sm btn-warning ml-2"
                            onclick="modalAction('{{ route('stok.editForm',$item->barang_id) }}')">
                            Edit
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- container modal kosong untuk AJAX --}}
<div id="myModal" class="modal fade" tabindex="-1" role="dialog"></div>
@endsection

@push('js')
<script>
    function modalAction(url) {
        $('#myModal')
            .load(url, function(){
                $(this).modal('show');
            });
    }

    $(function(){
        $('#table_stok').DataTable();
    });
</script>
@endpush
