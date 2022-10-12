@extends('layouts.ecommerce')

@section('title')
    <title>Return {{ $order->invoice }} - Rokata.id</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Return {{ $order->invoice }}</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ url('/') }}">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('customer.orders') }}">Return {{ $order->invoice }}</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->
    <section class="login_box_area p_120">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('layouts.ecommerce.module.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('customer.return', $order->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="">Alasan Return</label>
                                    <textarea name="reason" cols="5" rows="10" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Refund Transfer</label>
                                    <input type='text' name="refund_transfer" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Foto</label>
                                    <input type='file' name="photo" class="form-control" required>
                                </div>
                                <button class="btn btn-primary">Kirim</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
