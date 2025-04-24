@extends('layouts.template')
@section('content')
    <div class="d-flex flex-lg-row flex-column">
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    Pembeli
                </div>
                <div class="card-body">
                    <div class="d-flex flex-lg-row flex-sm-column flex-md-row justify-content-between">
                        <div class="form-group col-lg-6 col-md-12 col-sm-12">
                            <label for="nama-pembeli">Nama Pembeli</label>
                            <input type="text" class="form-control" id="nama-pembeli" name="pembeli"
                                placeholder="Agus Hartono" value="{{ $penjualan->pembeli }}">
                        </div>
                        <div class="form-group col-lg-6 col-md-12 col-sm-12">
                            <label for="nama-pembeli">Tanggal Pembelian</label>
                            <input type="date" class="form-control" id="tanggal-penjualan" name="tanggal_penjualan"
                                value="{{ date('Y-m-d', strtotime($penjualan->penjualan_tanggal)) }}">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card">
                <div class="card-header">
                    Barang Tersedia
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="barang">Pilih Barang</label>
                        <select class="form-select w-100" aria-label="Select barang" id="select-barang">
                            {{-- @foreach ($barang as $b)
                                <option value="{{ $b->barang_id }}" name="{{ $b->barang_nama }}"
                                    stock="{{ $b->stock_available }}" price="{{ $b->harga_jual }}">{{ $b->barang_nama }} -
                                    {{ 'Rp ' . number_format($b->harga_jual, 0, ',', '.') }} -
                                    {{ '( Stock: ' . $b->stock_available . ' )' }}
                                </option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="btn btn-primary" id="btn-tambah">Tambah</div>
                </div>
            </div>
            {{-- <div class="card">
                <div class="card-header">
                    Barang Tersedia
                </div>
                <div class="card-body">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <label for="nama-pembeli">Masukkan Keyword</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Mouse Gaming"
                            value="">
                    </div>
                    <div class="d-flex flex-lg-row flex-sm-column flex-md-row justify-content-between">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="border border-primary rounded p-3 product-card">
                                <h5 class="fs-4">Mouse Gaming</h5>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-success">Rp 250.000</span>
                                    <span class="badge bg-info px-2 py-1 fs-6">Stok: 15</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> --}}
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Keranjang</h5>
                </div>
                <div class="card-body">
                    <div id="cart-items">
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="fw-bold">Total:</h6>
                        <h6 class="fw-bold text-success" id="cart-total">Rp. 0</h6>
                    </div>

                    <button id='btn-process' class="btn btn-success w-100">
                        <i class="fas fa-check-circle me-2"></i>Proses
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
    <style>
        .product-card {
            border-radius: 0.5rem !important;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .icon-btn {
            width: 28px;
            height: 28px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }

        .qty-input {
            width: 40px;
            height: 28px;
            padding: 0;
            text-align: center;
        }
    </style>
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function() {

            let barangs = @json($barang);
            renderSelectOptions();
            $('#select-barang').select2({
                placeholder: 'Pilih Barang',
                theme: 'bootstrap-5'
            });

            let cart = @json($penjualan_detail).map(item => {
                return {
                    id: item.barang_id,
                    name: item.barang_nama,
                    price: item.harga_jual,
                    quantity: item.jumlah,
                    totalPrice: item.harga_jual * item.jumlah
                };
            });

            console.log(cart);

            let total = 0;

            renderCartItems();
            sumTotal();



            $('#btn-tambah').on('click', function() {

                if ($('#select-barang').val() == null) {
                    Swal.fire({
                        icon: 'error',
                        title: `Pilih Barang`,
                        text: `Silahkan pilih barang terlebih dahulu`,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }

                let barangId = $('#select-barang').val();
                let barangNama = $('#select-barang option:selected').attr('name');
                let barangHarga = parseInt($('#select-barang option:selected').attr('price'));
                let barangStock = parseInt($('#select-barang option:selected').attr('stock'));

                if (barangStock <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: `Stock Habis`,
                        text: `Barang ${barangNama} tidak tersedia`,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }

                let existingItemIndex = cart.findIndex(item => item.id == barangId);
                let barangIndex = barangs.findIndex(barang => barang.barang_id == barangId);
                // decrease stock available from barangs
                barangs[barangIndex].stock_available -= 1;

                if (existingItemIndex !== -1) {
                    cart[existingItemIndex].quantity += 1;
                    cart[existingItemIndex].totalPrice += barangHarga;
                } else {
                    cart.push({
                        id: barangId,
                        name: barangNama,
                        price: barangHarga,
                        quantity: 1,
                        totalPrice: barangHarga
                    });
                }


                console.log(cart);

                renderCartItems();
                renderSelectOptions();
                sumTotal();

                //$('#select-barang').val(null).trigger('change');
                //$('#select-barang').focus();

            })

            function decreaseQuantity(itemId) {
                let existingItemIndex = cart.findIndex(cartItem => cartItem.id == itemId);
                if (existingItemIndex !== -1) {

                    // check if stock is available from barangs
                    let barangId = cart[existingItemIndex].id;
                    let barangIndex = barangs.findIndex(barang => barang.barang_id == barangId);
                    barangs[barangIndex].stock_available += 1;

                    if (cart[existingItemIndex].quantity > 1) {
                        cart[existingItemIndex].quantity -= 1;
                        cart[existingItemIndex].totalPrice -= cart[existingItemIndex].price;
                    } else {
                        cart.splice(existingItemIndex, 1);
                    }
                }

                sumTotal();
                renderSelectOptions();
            }

            function increaseQuantity(itemId) {
                let existingItemIndex = cart.findIndex(cartItem => cartItem.id == itemId);
                if (existingItemIndex !== -1) {

                    // check if stock is available from barangs
                    let barangId = cart[existingItemIndex].id;
                    let barangIndex = barangs.findIndex(barang => barang.barang_id == barangId);
                    let stockAvailable = barangs[barangIndex].stock_available;

                    if (stockAvailable <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: `Stock Habis`,
                            text: `Barang ${cart[existingItemIndex].name} tidak tersedia`,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        return;
                    }

                    // decrease stock available from barangs
                    barangs[barangIndex].stock_available -= 1;
                    cart[existingItemIndex].quantity += 1;
                    cart[existingItemIndex].totalPrice += cart[existingItemIndex].price;
                }

                sumTotal();
                renderSelectOptions();
            }

            function removeItem(itemId) {
                let existingItemIndex = cart.findIndex(cartItem => cartItem.id == itemId);
                if (existingItemIndex !== -1) {

                    // check if stock is available from barangs
                    let barangId = cart[existingItemIndex].id;
                    let barangIndex = barangs.findIndex(barang => barang.barang_id == barangId);
                    barangs[barangIndex].stock_available += cart[existingItemIndex].quantity;
                    cart.splice(existingItemIndex, 1);
                    console.log('item removed from cart');
                } else {
                    console.log('item not found in cart');
                }

                sumTotal();
                renderSelectOptions();
            }

            $('#cart-items').on('click', '#qty-decrease', function() {
                let itemId = $(this).closest('div[data-id]').data('id');
                decreaseQuantity(itemId);
                renderCartItems();
                $('#cart-total').text(`Rp. ${total.toLocaleString()}`);
            });

            $('#cart-items').on('click', '#qty-increase', function() {
                let itemId = $(this).closest('div[data-id]').data('id');
                increaseQuantity(itemId);
                renderCartItems();
                $('#cart-total').text(`Rp. ${total.toLocaleString()}`);
            });

            $('#cart-items').on('click', '#remove-item', function() {

                console.log('remove item');

                let itemId = $(this).closest('div[data-id]').data('id');
                console.log(itemId);
                removeItem(itemId);
                renderCartItems();
            });


            function sumTotal() {

                total = 0;

                cart.forEach(item => {
                    total += item.totalPrice;
                });
                $('#cart-total').text(`Rp. ${total.toLocaleString()}`);
            }

            function renderSelectOptions() {

                $('#select-barang').empty();

                let barangOptions = barangs.map(barang => {
                    return `<option value="${barang.barang_id}" name="${barang.barang_nama}" stock="${barang.stock_available}" price="${barang.harga_jual}">${barang.barang_nama} - Rp ${barang.harga_jual} - ( Stock: ${barang.stock_available} )</option>`;
                });

                $('#select-barang').html(barangOptions.join(''));
            }

            function renderCartItems() {
                let cartItemsContainer = $('#cart-items');
                cartItemsContainer.empty();

                cart.forEach(item => {
                    let cartItem = `
                                 <div class="d-flex align-items-center mb-3 pb-3 border-bottom" data-id="${item.id}">
                            <div class="col-7">
                                <h6 class="mb-1">${item.name}</h6>
                                <p class="text-muted mb-0">Rp ${item.price} × <span class="fw-bold">${item.quantity}</span> = <span
                                        class="text-success fw-bold">Rp ${item.totalPrice}</span></p>
                            </div>
                            <div class="col-5 d-flex justify-content-end align-items-center">
                                <div class="d-flex align-items-center me-2">
                                    <button id="qty-decrease" class="btn btn-outline-secondary p-1 qty-decrease" type="button"
                                        style="width: 28px; height: 28px; display: inline-flex; justify-content: center; align-items: center;">
                                        <i class="fas fa-minus fa-xs"></i>
                                    </button>
                                    <input type="text" class="form-control text-center mx-1" value="${item.quantity}" readonly
                                        style="width: 40px; height: 28px; padding: 0;">
                                    <button id="qty-increase" class="btn btn-outline-secondary p-1 qty-increase" type="button"
                                        style="width: 28px; height: 28px; display: inline-flex; justify-content: center; align-items: center;">
                                        <i class="fas fa-plus fa-xs"></i>
                                    </button>
                                </div>
                                <button id="remove-item" class="btn btn-danger p-1" type="button" title="Hapus item"
                                    style="width: 28px; height: 28px; display: inline-flex; justify-content: center; align-items: center;">
                                    <i class="fas fa-trash fa-xs"></i>
                                </button>
                            </div>
                        </div>
                            `;

                    cartItemsContainer.append(cartItem);
                });

            }


            $('#btn-process').on('click', function() {

                let pembeli = $('#nama-pembeli').val();

                if (!pembeli || pembeli.length < 3) {
                    Swal.fire({
                        icon: 'error',
                        title: `Nama Pembeli`,
                        text: `Silahkan masukkan nama pembeli lebih dari 3 karakter`,
                    })
                    return;
                }

                let tanggalPenjualan = $('#tanggal-penjualan').val();

                if (!tanggalPenjualan) {
                    Swal.fire({
                        icon: 'error',
                        title: `Tanggal Penjualan`,
                        text: `Silahkan masukkan tanggal penjualan`,
                    })
                    return;
                }

                if (cart.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: `Keranjang Kosong`,
                        text: `Silahkan pilih barang terlebih dahulu`,
                    })
                    return;
                }

                let data = {
                    penjualan_id: {{ $penjualan->penjualan_id }},
                    pembeli: pembeli,
                    penjualan_tanggal: tanggalPenjualan,
                    barang: cart
                };

                $.ajax({
                    url: "{{ url('penjualan/edit') }}",
                    type: "POST",
                    dataType: "json",
                    contentType: "application/json",
                    processData: false,
                    data: JSON.stringify(data),
                    success: function(response) {
                        console.log(response);
                        if (response.status == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Transaksi berhasil dilakukan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            window.location.href = "{{ url('penjualan') }}";
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Transaksi gagal dilakukan',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

            })
        });
    </script>
@endpush