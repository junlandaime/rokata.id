@extends('layouts.ecommerce')

@section('title')
    <title>Keranjang Belanja - Dw Ecommerce</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Shopping Cart</h1>
                    <nav class="d-flex align-items-center">
                        <a href="/">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="/cart">Cart</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">

                <form action="{{ route('front.update_cart') }}" method="post">
                    @csrf

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($carts as $item)
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="{{ asset('storage/products/' . $item['product_image']) }}"
                                                        width="100px" height="100px" alt="{{ $item['product_name'] }}">
                                                </div>
                                                <div class="media-body">
                                                    <p>{{ $item['product_name'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>Rp {{ number_format($item['product_price']) }}</h5>
                                        </td>
                                        <td>
                                            <div class="product_count">
                                                <input type="text" name="qty[]" id="sst{{ $item['product_id'] }}"
                                                    maxlength="12" value="{{ $item['qty'] }}" title="Quantity:"
                                                    class="input-text qty">
                                                <input type="hidden" name="product_id[]" value="{{ $item['product_id'] }}"
                                                    class="form-control">
                                                <button
                                                    onclick="var result = document.getElementById('sst{{ $item['product_id'] }}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                                                    class="increase items-count" type="button"><i
                                                        class="lnr lnr-chevron-up"></i></button>
                                                <button
                                                    onclick="var result = document.getElementById('sst{{ $item['product_id'] }}'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                                                    class="reduced items-count" type="button"><i
                                                        class="lnr lnr-chevron-down"></i></button>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>Rp {{ number_format($item['product_price'] * $item['qty']) }}</h5>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Tidak ada belanjaan</td>
                                    </tr>
                                @endforelse


                                <tr class="bottom_button">
                                    <td>
                                        <button class="gray_btn">Update Cart</button>
                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    {{-- <td>
                                        <div class="cupon_text d-flex align-items-center">
                                            <input type="text" placeholder="Coupon Code">
                                            <a class="primary-btn" href="#">Apply</a>
                                            <a class="gray_btn" href="#">Close Coupon</a>
                                        </div>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <h5>Subtotal</h5>
                                    </td>
                                    <td>
                                        <h5>Rp {{ number_format($subtotal) }}</h5>
                                    </td>
                                </tr>
                                {{-- <tr class="shipping_area">
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <h5>Shipping</h5>
                                    </td>

                                </tr> --}}
                                <tr class="out_button_area">
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <div class="checkout_btn_inner d-flex align-items-center">
                                            <a class="gray_btn" href="{{ route('front.product') }}"><small>Continue
                                                    Shopping</small></a>
                                            <a class="primary-btn" href="{{ route('front.checkout') }}"><small>Proceed to
                                                    checkout</small></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->
@endsection
