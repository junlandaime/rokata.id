@extends('layouts.ecommerce')

@section('title')
    <title>Keranjang Belanja - Rokata.id</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Confirmation</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Confirmation</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Order Details Area =================-->
    {{-- @dd($order); --}}
    {{-- @dd($detail[0]->product->name); --}}

    <section class="order_details section_gap">
        <div class="container">
            <div class="returning_customer pb-5">
                <h3 class="title_confirmation pb-n3">Terimakasih. Pesananmu telah kami Terima.</h3>
                <div class="check_title text-center">
                    @if (!auth()->guard('customer')->check())
                        <h2>Silahkan cek Email anda untuk aktivasi akun dan melihat password untuk Login.</a></h2>
                    @else
                    @endif
                    <p>Lakukan Konfirmasi Pemesanan dan Pembayaran di halaman Dashboar Anda</p>
                </div>

            </div>

            <div class="row order_d_inner">
                <div class="col-lg-6">
                    <div class="details_item">
                        <h4>Order Info</h4>
                        <ul class="list">
                            <li><a href="#"><span>Order number</span> : {{ $order->invoice }}</a></li>
                            <li><a href="#"><span>Date</span> : {{ $order->created_at }}s</a></li>
                            <li><a href="#"><span>Total</span> : Rp
                                    {{ number_format($order->subtotal + $order->ongkir) }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="details_item">
                        <h4>Shipping Address</h4>
                        <ul class="list">
                            <li><a href="#"><span>Address</span> : {{ $order->customer_address }}</a></li>
                            <li><a href="#"><span>City</span> : {{ $order->district->city->name }}</a></li>
                            <li><a href="#"><span>Country</span> : Indonesia</a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="order_details_table">
                <h2>Order Details</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($detail as $row)
                                <tr>
                                    <td>
                                        <p>{{ $row->product->name }}</p>
                                    </td>
                                    <td>
                                        <h5>x {{ $row->qty }}</h5>
                                    </td>
                                    <td>
                                        <p>Rp {{ number_format($row->price * $row->qty) }}</p>
                                    </td>
                                </tr>


                            @empty
                            @endforelse
                            <tr>
                                <td>
                                    <h4>Subtotal</h4>
                                </td>
                                <td>
                                    <h5></h5>
                                </td>
                                <td>
                                    <p>Rp {{ number_format($order->subtotal) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>Pengiriman</h4>
                                </td>
                                <td>
                                    <h5></h5>
                                </td>
                                <td>
                                    <p>{{ $order->courier_service }}: Rp {{ number_format($order->ongkir) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>Total</h4>
                                </td>
                                <td>
                                    <h5></h5>
                                </td>
                                <td>
                                    <p>Rp {{ number_format($order->subtotal + $order->ongkir) }}</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--================End Order Details Area =================-->
@endsection
