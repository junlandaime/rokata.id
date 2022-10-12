@extends('layouts.ecommerce')

@section('title')
    <title>Order {{ $order->invoice }} - DW Ecommerce</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Order {{ $order->invoice }}</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Order {{ $order->invoice }}</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Login Box Area =================-->
    <section class="login_box_area p_120 my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('layouts.ecommerce.module.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Data Pelanggan</h4>
                                </div>
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td width="30%">InvoiceID</td>
                                            <td width="5%">:</td>
                                            <th><a href="{{ route('customer.order_pdf', $order->invoice) }}"
                                                    target="_blank"><strong>{{ $order->invoice }}</strong></a></th>
                                        </tr>
                                        <tr>
                                            <td width="30%">Nama Lengkap</td>
                                            <td width="5%">:</td>
                                            <th>{{ $order->customer_name }}</th>
                                        </tr>
                                        <tr>
                                            <td>No Telp</td>
                                            <td>:</td>
                                            <th>{{ $order->customer_phone }}</th>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>:</td>
                                            <th>{{ $order->customer_address }}, {{ $order->district->name }}
                                                {{ $order->district->city->name }},
                                                {{ $order->district->city->province->name }}</th>
                                        </tr>
                                        <tr>
                                            <td>Subtotal</td>
                                            <td>:</td>
                                            <th>{{ $order->subtotal }}</th>
                                        </tr>
                                        <tr>
                                            <td>ongkir</td>
                                            <td>:</td>
                                            <th>{{ $order->ongkir }} - {{ $order->courier_service }}</th>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td>:</td>
                                            <th>{{ $order->subtotal + $order->ongkir }}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        Pembayaran

                                        @if ($order->status == 0)
                                            <a href="{{ url('member/payment?invoice=' . $order->invoice) }}"
                                                class="btn btn-primary btn-sm float-right">Konfirmasi</a>
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    @if ($order->payment)
                                        <table>
                                            <tr>
                                                <td width="30%">Nama Pengirim</td>
                                                <td width="5%"></td>
                                                <td>{{ $order->payment->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Transfer</td>
                                                <td></td>
                                                <td>{{ $order->payment->transfer_date }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jumlah Transfer</td>
                                                <td></td>
                                                <td>Rp {{ number_format($order->payment->amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tujuan Transfer</td>
                                                <td></td>
                                                <td>{{ $order->payment->transfer_to }}</td>
                                            </tr>
                                            <tr>
                                                <td>Bukti Transfer</td>
                                                <td></td>
                                                <td>
                                                    <img src="{{ asset('storage/payment/' . $order->payment->proof) }}"
                                                        width="50px" height="50px" alt="">
                                                    <a href="{{ asset('storage/payment/' . $order->payment->proof) }}"
                                                        target="_blank">Lihat Detail</a>
                                                </td>
                                            </tr>
                                        </table>
                                    @else
                                        <h4 class="text-center">Belum ada data pembayaran</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Detail</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nama Produk</th>
                                                    <th>Harga</th>
                                                    <th>Quantity</th>
                                                    <th>Berat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($order->details as $row)
                                                    <tr>
                                                        <td>{{ $row->product->name }}</td>
                                                        <td>{{ number_format($row->price) }}</td>
                                                        <td>{{ $row->qty }} Item</td>
                                                        <td>{{ $row->weight }} gr</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
