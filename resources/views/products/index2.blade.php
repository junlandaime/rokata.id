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
        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    List Product

                                    <a href="{{ route('product.bulk') }}" class="btn btn-danger btn-sm float-right">Mass
                                        Upload</a>
                                    <a href="{{ route('product.create') }}"
                                        class="btn btn-primary btn-sm float-right">Tambah</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <form action="{{ route('product.index') }}" method="get">
                                    <div class="input-group mb-3 col-md-3 float-right">
                                        <input type="text" class="form-control" name="q" placeholder="Cari..."
                                            value="{{ request()->q }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="button">Cari</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Produk</th>
                                                <th>Harga</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($product as $row)
                                                <tr>
                                                    <td>
                                                        <img src="{{ asset('storage/products/' . $row->image) }}"
                                                            alt="{{ $row->name }}" width="100px" height="100px">
                                                    </td>
                                                    <td>
                                                        <strong>{{ $row->name }}</strong><br>

                                                        <label>Kategori: <span
                                                                class="badge bg-gradient-info">{{ $row->category->name }}</span></label><br>
                                                        <label>Berat: <span
                                                                class="badge bg-gradient-info">{{ $row->weight }}</span></label>
                                                    </td>
                                                    <td>Rp {{ number_format($row->price) }}</td>
                                                    <td>{{ $row->created_at->format('d-m-Y') }}</td>

                                                    <td><span
                                                            class="badge bg-gradient-success">{!! $row->status_label !!}</span>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('product.destroy', $row->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="{{ route('product.edit', $row->id) }}"
                                                                class="btn btn-warning btn-sm">Edit</a>
                                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
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
        </div>
    </main>
@endsection

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>List Product

                        <a href="{{ route('product.bulk') }}" class="btn btn-danger btn-sm float-right">Mass
                            Upload</a>
                        <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm float-right">Tambah</a>


                    </h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
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
                                                    <p class="text-xs text-secondary mb-0">Berat: {{ $row->weight }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">Rp
                                                {{ number_format($row->price) }}</p>
                                            <p class="text-xs text-secondary mb-0">Created At
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span
                                                class="badge badge-sm bg-gradient-success">{!! $row->status_label !!}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $row->created_at->format('d-m-Y') }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('product.destroy', $row->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('product.edit', $row->id) }}"
                                                    class="text-secondary font-weight-bold text-xs"
                                                    data-toggle="tooltip" data-original-title="Edit user">
                                                    Edit
                                                </a>
                                                <button class="text-secondary font-weight-bold text-xs">Hapus</button>
                                            </form>
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
