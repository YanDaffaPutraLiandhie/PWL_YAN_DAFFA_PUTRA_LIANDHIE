@extends('layouts.template')
@section('content')
<div class="row gx-3">
  <div class="col-lg-7">
    <form method="POST" action="{{ route('penjualan.store') }}">
      @csrf
      <div class="card mb-3">
        <div class="card-header">Pembeli</div>
        <div class="card-body row">
          <div class="col-md-6 mb-2">
            <label>Nama Pembeli</label>
            <input type="text" name="pembeli" value="{{ old('pembeli') }}"
                   class="form-control @error('pembeli') is-invalid @enderror">
            @error('pembeli')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6 mb-2">
            <label>Tanggal</label>
            <input type="date" name="penjualan_tanggal" value="{{ old('penjualan_tanggal', date('Y-m-d')) }}"
                   class="form-control @error('penjualan_tanggal') is-invalid @enderror">
            @error('penjualan_tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">Barang Tersedia</div>
        <div class="card-body d-flex gap-2">
          <select id="select-barang" class="form-select">
            @foreach($barang as $b)
              <option value="{{ $b['barang_id'] }}"
                      data-name="{{ $b['barang_nama'] }}"
                      data-price="{{ $b['harga_jual'] }}"
                      data-stok="{{ $b['stok_available'] }}">
                {{ $b['barang_nama'] }} – Rp{{ number_format($b['harga_jual'],0,',','.') }} (Stok: {{ $b['stok_available'] }})
              </option>
            @endforeach
          </select>
          <button type="button" id="btn-tambah" class="btn btn-primary">Tambah</button>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">Keranjang</div>
        <div class="card-body">
          <div id="cart-items"></div>
          <strong>Total: <span id="cart-total">Rp 0</span></strong>
        </div>
      </div>
      <div id="inputs-hidden"></div>

      <button type="submit" class="btn btn-success">Proses</button>
    </form>
  </div>
</div>
@endsection

@push('js')
<script>
$(function(){
  const barangList = @json($barang);
  let cart = [];
  const fmt = n => 'Rp '+n.toLocaleString('id');

  function renderCart() {
    let html='', total=0;
    cart.forEach((it,i)=>{
      total+=it.total;
      html += `<div class="d-flex justify-content-between align-items-center mb-2">
        <span>${it.name} x${it.qty} = ${fmt(it.total)}</span>
        <div>
          <button class="btn btn-sm btn-secondary me-1" onclick="updateQty(${i}, -1)">-</button>
          <button class="btn btn-sm btn-secondary" onclick="updateQty(${i}, 1)">+</button>
        </div>
      </div>`;
    });
    $('#cart-items').html(html);
    $('#cart-total').text(fmt(total));

    let inputs='';
    cart.forEach((it,i)=>{
      inputs+=`<input type="hidden" name="barang[${i}][id]" value="${it.id}">`;
      inputs+=`<input type="hidden" name="barang[${i}][quantity]" value="${it.qty}">`;
    });
    $('#inputs-hidden').html(inputs);
  }

  window.updateQty = function(index, delta) {
    const item = cart[index];
    const stok = $('#select-barang option[value="'+item.id+'"]').data('stok');
    if (item.qty + delta <= 0) {
      cart.splice(index, 1);
    } else if (item.qty + delta > stok) {
      alert('Jumlah melebihi stok tersedia');
      return;
    } else {
      item.qty += delta;
      item.total = item.qty * (item.total / (item.qty - delta));
    }
    renderCart();
  }

  $('#btn-tambah').click(()=>{
    const sel = $('#select-barang option:selected');
    const id = sel.val(), name = sel.data('name'), price = sel.data('price');
    const stok = sel.data('stok');
    if (!id || stok<=0) return alert('Stok habis');
    let idx = cart.findIndex(x=>x.id==id);
    if (idx>=0) {
      if (cart[idx].qty + 1 > stok) return alert('Jumlah melebihi stok tersedia');
      cart[idx].qty++;
      cart[idx].total += price;
    }
    else {
      cart.push({id,name,qty:1,total:price});
    }
    renderCart();
  });
});
</script>
@endpush