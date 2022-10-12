@extends('layouts.ecommerce')

@section('title')
    <title>Daftar Komisi - Rokata.id</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Daftar Komisi</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ url('/') }}">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('customer.affiliate') }}">Daftar Komisi</a>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Komisi Afiliasi</h4>
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
                                                    <th>Komisi</th>
                                                    <th>Status</th>
                                                    <th>Tanggal</th>
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
                                                        <td>Rp {{ number_format($row->commission) }}</td>
                                                        <td>{!! $row->ref_status_label !!}</td>
                                                        <td>{{ $row->created_at }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">Tidak ada komisi</td>
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
