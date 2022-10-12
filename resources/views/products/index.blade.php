@extends('layouts.admin')

@section('title')
    <title>List Product</title>
@endsection

@section('content')
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Product</li>
        </ol>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>List Product

                                <a href="{{ route('product.bulk') }}" class="btn btn-danger btn-sm float-right">Mass
                                    Upload</a>
                                <a href="{{ route('product.create') }}"
                                    class="btn btn-primary btn-sm float-right">Tambah</a>


                            </h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            @if (session('success'))
                                <div class="alert alert-warning">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <form action="{{ route('product.index') }}" method="get">
                                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text text-body"><i class="fas fa-search"
                                                aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" name="q" placeholder="Cari..."
                                            value="{{ request()->q }}">
                                    </div>
                                </div>
                            </form>
                            {{-- <div class="input-group mb-3 col-md-3 float-right">
                                <input type="text" class="form-control" name="q" placeholder="Cari..."
                                    value="{{ request()->q }}">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="button">Cari</button>
                                </div>
                            </div> --}}

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Produk</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Harga</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Created At</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($product as $row)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="{{ asset('storage/products/' . $row->image) }}"
                                                                class="avatar avatar-sm me-3" alt="{{ $row->name }}">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $row->name }}</h6>
                                                            <p class="text-xs text-secondary mb-0">Kategori:
                                                                {{ $row->category->name }}</p>
                                                            <p class="text-xs text-secondary mb-0">Berat:
                                                                {{ $row->weight }} gr
                                                            </p>
                                                            <p class="text-xs text-secondary mb-0">Stock:
                                                                <span
                                                                    class="badge badge-sm bg-gradient-primary">{{ $row->stock }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">Rp
                                                        {{ number_format($row->price) }}</p>
                                                    <p class="text-xs text-secondary mb-0">
                                                    </p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span>{!! $row->status_label !!}</span>
                                                    @if ($row->stock == 0)
                                                        <span class="badge badge-sm bg-gradient-secondary">Stock
                                                            Empty</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $row->created_at->format('d-m-Y') }}</span>
                                                </td>
                                                <td class="align-middle">


                                                    <form id="deleteProd" action="{{ route('product.destroy', $row->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <a class="btn btn-link text-danger text-gradient px-3 mb-0"
                                                        href="{{ route('product.destroy', $row->id) }}"
                                                        onclick="event.preventDefault();
                                                        document.getElementById('deleteProd').submit();"><i
                                                            class="far fa-trash-alt me-2"></i>Delete</a>
                                                    <a class="btn btn-link text-dark px-3 mb-0"
                                                        href="{{ route('product.edit', $row->id) }}"><i
                                                            class="fas fa-pencil-alt text-dark me-2"
                                                            aria-hidden="true"></i>Edit</a>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="5">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {!! $product->links() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
