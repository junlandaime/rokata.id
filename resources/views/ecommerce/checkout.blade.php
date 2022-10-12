@extends('layouts.ecommerce')

@section('title')
    <title>Checkout - Rokata.id</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Checkout</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="single-product.html">Checkout</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Checkout Area =================-->
    <section class="checkout_area section_gap">
        <div class="container">
            {{-- <div class="returning_customer">
                <div class="check_title">
                    <h2>Returning Customer? <a href="#">Click here to login</a></h2>
                </div>
                <p>If you have shopped with us before, please enter your details in the boxes below. If you are a new
                    customer, please proceed to the Billing & Shipping section.</p>
                <form class="row contact_form" action="#" method="post" novalidate="novalidate">
                    <div class="col-md-6 form-group p_star">
                        <input type="text" class="form-control" id="name" name="name">
                        <span class="placeholder" data-placeholder="Username or Email"></span>
                    </div>
                    <div class="col-md-6 form-group p_star">
                        <input type="password" class="form-control" id="password" name="password">
                        <span class="placeholder" data-placeholder="Password"></span>
                    </div>
                    <div class="col-md-12 form-group">
                        <button type="submit" value="submit" class="primary-btn">login</button>
                        <div class="creat_account">
                            <input type="checkbox" id="f-option" name="selector">
                            <label for="f-option">Remember me</label>
                        </div>
                        <a class="lost_pass" href="#">Lost your password?</a>
                    </div>
                </form>
            </div>
            <div class="cupon_area">
                <div class="check_title">
                    <h2>Have a coupon? <a href="#">Click here to enter your code</a></h2>
                </div>
                <input type="text" placeholder="Enter coupon code">
                <a class="tp_btn" href="#">Apply Coupon</a>
            </div> --}}


            <div class="billing_details">
                <div class="row">
                    <div class="col-lg-8">
                        <h3>Billing Details</h3>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form class="row contact_form" action="{{ route('front.store_checkout') }}" method="post"
                            novalidate="novalidate">
                            @csrf
                            <div class="col-md-12 form-group p_star">
                                <label for="">Nama Lengkap</label>
                                <input type="text" class="form-control" id="first" name="customer_name" required>

                                <!-- UNTUK MENAMPILKAN JIKA TERDAPAT ERROR VALIDASI -->
                                <p class="text-danger">{{ $errors->first('customer_name') }}</p>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <label for="">No Telp</label>
                                <input type="text" class="form-control" id="number" name="customer_phone" required>
                                <p class="text-danger">{{ $errors->first('customer_phone') }}</p>
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <label for="">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ auth()->guard('customer')->check()? auth()->guard('customer')->user()->email: '' }}"
                                    required {{ auth()->guard('customer')->check()? 'readonly': '' }}>
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            </div>
                            {{-- <div class="col-md-6 form-group p_star">
                                <label for="">Email</label>
                                @if (auth()->guard('customer')->check())
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="{{ auth()->guard('customer')->user()->email }}" 
                                    required {{ auth()->guard('customer')->check() ? 'readonly':'' }}>
                                @else
                                <input type="email" class="form-control" id="email" name="email"
                                    required>
                                @endif
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            </div> --}}
                            <div class="col-md-12 form-group p_star">
                                <label for="">Alamat Lengkap</label>
                                <input type="text" class="form-control" id="add1" name="customer_address" required>
                                <p class="text-danger">{{ $errors->first('customer_address') }}</p>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Propinsi</label>
                                <select class="form-control" name="province_id" id="province_id" required>
                                    <option value="">Pilih Propinsi</option>
                                    <!-- LOOPING DATA PROVINCE UNTUK DIPILIH OLEH CUSTOMER -->
                                    @foreach ($provinces as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-danger">{{ $errors->first('province_id') }}</p>
                            </div>

                            <!-- ADAPUN DATA KOTA DAN KECAMATAN AKAN DI RENDER SETELAH PROVINSI DIPILIH -->
                            <div class="col-md-12 form-group p_star">
                                <label for="">Kabupaten / Kota</label>
                                <select class="form-control" name="city_id" id="city_id" required>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('city_id') }}</p>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Kecamatan</label>
                                <select class="form-control" name="district_id" id="district_id" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('district_id') }}</p>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Kurir</label>
                                <input type="hidden" name="weight" id="weight" value="{{ $weight }}">
                                <select class="form-control" name="courier" id="courier" required>
                                    <option value="">Pilih Kurir</option>
                                    <option value="jne">JNE</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">POS</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('courier') }}</p>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <label for="">Service</label>
                                <select class="form-control" name="service" id="service" required>
                                    <option value="">Pilih Kurir</option>
                                    <option value="jne">JNE</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">POS</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('service') }}</p>
                            </div>


                    </div>
                    <div class="col-lg-4">
                        <div class="order_box">
                            <h2>Your Order</h2>
                            <ul class="list">
                                <li><a href="#">Product <span>Total</span></a></li>
                                @foreach ($carts as $cart)
                                    <li><a href="#">{{ \Str::limit($cart['product_name'], 10) }} <span
                                                class="middle">x
                                                {{ $cart['qty'] }}</span> <span class="last">Rp
                                                {{ number_format($cart['product_price']) }}</span></a></li>
                                @endforeach

                            </ul>
                            <ul class="list list_2">
                                <li><a href="#">Subtotal <span>Rp {{ number_format($subtotal) }}</span></a></li>
                                <li><a href="#">Shipping <span id="ongkir">Rp 0</span></a></li>
                                <li><a href="#">Total <span id="total">Rp
                                            {{ number_format($subtotal) }}</span></a></li>
                            </ul>

                            <button class="primary-btn border-0">Simpan Pesanan</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--================End Checkout Area =================-->
    {{-- @dd($body) --}}
@endsection

@section('js')
    <script>
        $('#province_id').on('change', function() {
            // console.log('OKe');
            $.ajax({
                url: "/api/city",
                type: "GET",
                data: {
                    province_id: $(this).val()
                },
                success: function(html) {
                    // console.log('oke');
                    $('#city_id').empty()

                    $('#city_id').append('<option value=""> Select Town/City</option>')
                    $.each(html.data, function(key, item) {
                        $('#city_id').append('<option value="' + item.id + '">' + item.name +
                            '</option>')
                    })
                }
            });
        })

        $('#city_id').on('change', function() {
            $.ajax({
                url: "/api/district",
                type: "GET",
                data: {
                    city_id: $(this).val()
                },
                success: function(html) {
                    $('#district_id').empty()
                    $('#district_id').append('<option value=""> Select District</option>')
                    $.each(html.data, function(key, item) {
                        $('#district_id').append('<option value="' + item.id + '">' + item
                            .name +
                            '</option>')
                    })
                }
            });
        })

        //JIKA KECAMATAN DIPILIH
        $('#courier').on('change', function() {
            //MEMBUAT EFEK LOADING SELAMA PROSES REQUEST BERLANGSUNG
            $('#service').empty()
            $('#service').append('<option value="">Loading...</option>')

            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGAMBIL DATA API
            $.ajax({
                url: "/api/cost",
                type: "post",
                data: {
                    destination: $('#city_id').val(),
                    weight: $('#weight').val(),
                    courier: $('#courier').val()
                },
                success: function(html) {

                    // console.log(html);
                    //BERSIHKAN AREA SELECT BOX
                    $('#service').empty()
                    $('#service').append('<option value="">Pilih Service</option>')
                    // console.log(html.rajaongkir.results[0].costs);

                    //LOOPING DATA ONGKOS KIRIM
                    $.each(html.rajaongkir.results[0].costs, function(key, item) {
                        let description = item.description + ' - ' + item.service + ' (Rp ' +
                            item
                            .cost[0].value + ')'
                        let value = item.description + '-' + item.service + '-' + item.cost[0]
                            .value
                        //DAN MASUKKAN KE DALAM OPTION SELECT BOX
                        $('#service').append('<option value="' + value + '">' + description +
                            '</option>')



                    })
                }
            });
        })

        //JIKA KURIR DIPILIH
        $('#service').on('change', function() {
            //UPDATE INFORMASI BIAYA PENGIRIMAN
            let split = $(this).val().split('-')
            $('#ongkir').text('Rp ' + split[2])

            //UPDATE INFORMASI TOTAL (SUBTOTAL + ONGKIR)
            let subtotal = "{{ $subtotal }}"
            let total = parseInt(subtotal) + parseInt(split['2'])
            $('#total').text('Rp' + total)
        })
    </script>
@endsection

{{-- <script>
    $(document).ready(function()[
        loadCity($('#province_id').val(), 'bySelect').then(() => {
            loadDistrict($('#city_id').val(), 'bySelect');
        })
    })

    $('#province_id').on('change', function() {
        loadCity($(this).val(), '');
    })

    $('#city_id').on('change', function() {
        loadDistrict($(this).val(), '')
    })

    function loadCity(province_id, type) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "{{ url('/api/city') }}",
                type: "GET",
                data: {
                    province_id: province_id
                },
                success: function(html) {
                    $('#city_id').empty()
                    $('#city_id').append('<option value="">Pilih Kabupaten/Kota</option>')
                    $.each(html.data, function(key, item) {

                        $('#city_id').empty()
                        $('#city_id').append('<option value="' + item.id + '">' + item
                            .name + '</option>')
                        resolve()
                    })
                }
            });
        })
    }

    //CARA KERJANYA SAMA SAJA DENGAN FUNGSI DI ATAS
    function loadDistrict(city_id, type) {
        $.ajax({
            url: "{{ url('/api/district') }}",
            type: "GET",
            data: {
                city_id: city_id
            },
            success: function(html) {
                $('#district_id').empty()
                $('#district_id').append('<option value="">Pilih Kecamatan</option>')
                $.each(html.data, function(key, item) {
                    $('#district_id').empty()
                    $('#district_id').append('<option value="' + item.id + '">' +
                        item.name + '</option>')
                })
            }
        });
    }

    //jika kecamatan dipilih
        $('#district_id').on('change', function() {
            //membuat efek loading selama proses request berlangsung
            $('#courier').empty()
            $('#courier').append('<option value="">Loading...</option>')

            //mengirimkan permintaan ke server untuk mengambil data api
            $.ajax({
                url: "/api/cost",
                type: 'POST',
                data: {
                    destination: $(this).val(),
                    weight: $('#weight').val()
                },
                success: function(html) {
                    //bersihkan area select box
                    $('#courier').empty()
                    $('#courier').append('<option value="">Pilih Kurir</option>')

                    //looping data ongkos kirim
                    $.each(html.data.results, function(key, item) {
                        let courier = item.courier + ' - ' + item.service + ' (Rp' + item.cost +
                            ')'
                        let value = item.courier + ' - ' + item.service + '-' + item.cost
                        //dan masukkan ke dalma option select box
                        $('#courier').append('<option value="' + value '">' + courier +
                            '</option>')
                    })
                }
            });

        })

        //jika kurir dipilih
        $('#courier').on('change', function() {
            //update informasi biaya pengiriman
            let split = $(this).val().split('-')
            $('#ongkir').text('Rp ' + split[2])

            //update informasi total (suntotal + ongkir)
            let subtotal = "{{ $subtotal }}"
            let total = parseInt(subtotal) + parseInt(split[2])
            $(#total).text('Rp' + total)
        })
</script> --}}
