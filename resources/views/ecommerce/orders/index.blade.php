@extends('layouts.ecommerce')

@section('title')
    <title>List Pesanan - DW Ecommerce</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>List Pesanan</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">List Pesanan</a>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">List Pesanan</h4>
                                </div>
                                <div class="card-body">
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Invoice</th>
                                                    <th>Penerima</th>
                                                    <th>No Telp</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Tanggal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($orders as $row)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $row->invoice }}</strong><br>
                                                            @if ($row->return_count == 1)
                                                                <small>Return: {!! $row->return->status_label !!}</small>
                                                            @endif
                                                        </td>
                                                        <td>{{ $row->customer_name }}</td>
                                                        <td>{{ $row->customer_phone }}</td>
                                                        <td>Rp {{ number_format($row->subtotal + $row->ongkir) }}</td>
                                                        <td>{!! $row->status_label !!}</td>
                                                        <td>{{ $row->created_at }}</td>
                                                        <td>
                                                            <form action="{{ route('customer.order_accept') }}"
                                                                class="form-inline" onsubmit="return confirm('Kamu Yakin?')"
                                                                method="post">
                                                                @csrf
                                                                {{-- tombol view order kita bungkus dengan form agar rapi --}}
                                                                <a href="{{ route('customer.view_order', $row->invoice) }}"
                                                                    class="btn btn-primary btn-sm mr-1">Detail</a>

                                                                <input type="hidden" name="order_id"
                                                                    value="{{ $row->id }}">
                                                                @if ($row->status == 3 && $row->return_count == 0)
                                                                    <button class="btn btn-success btn-sm">Terima</button>
                                                                    {{-- tombol untuk mengarah ke halaman return --}}
                                                                    <a href="{{ route('customer.order_return', $row->invoice) }}"
                                                                        class="btn btn-danger btn-sm mt-1">Return</a>
                                                                @endif
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Tidak ada pesanan</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="float-right">
                                        {!! $orders->links() !!}
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
