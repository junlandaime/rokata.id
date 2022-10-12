@extends('layouts.ecommerce')

@section('title')
    <title>Dashboard - DW Ecommerce</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Member Dashboard</h1>
                    <nav class="d-flex align-items-center">
                        <a href="/">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Dashboard</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Login Box Area =================-->
    {{-- @dd($orders) --}}
    <section class="login_box_area p_120 my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('layouts.ecommerce.module.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3>Belum Dibayar</h3>
                                    <hr>
                                    <p>Rp {{ number_format($orders[0]->pending) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3>Dikirim</h3>
                                    <hr>
                                    <p>{{ $orders[0]->shipping }} Pesanan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3>Selesai</h3>
                                    <hr>
                                    <p>{{ $orders[0]->completeOrder }} Pesanan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
