@extends('layouts.ecommerce')

@section('title')
    <title>Jual Produk - Rokata.id</title>
@endsection

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Shop Category page</h1>
                    <nav class="d-flex align-items-center">
                        <a href="/">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="/product">Shop<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Product Category</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->
    <div class="container my-5">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="sidebar-categories">
                    <a href="/event">
                        <div class="head">Browse Categories</div>
                    </a>
                    <ul class="main-categories">
                        @foreach ($categories as $category)
                            <li class="main-nav-list"><a data-toggle="collapse"
                                    href="{{ $category->child_count > 0 ? '#' . $category->slug : url('/category/' . $category->slug) }}"
                                    aria-expanded="false" aria-controls="fruitsVegetable"><span
                                        class="lnr lnr-arrow-right"></span>{{ $category->name }}<span
                                        class="number">({{ $category->product->count() }})</span></a>
                                <ul class="collapse" id="{{ $category->slug }}" data-toggle="collapse" aria-expanded="true"
                                    aria-controls="{{ $category->slug }}">
                                    @foreach ($category->child as $child)
                                        <li class="main-nav-list child"><a
                                                href="{{ url('/category/' . $child->slug) }}">{{ $child->name }}<span
                                                    class="number">(13)</span></a></li>
                                    @endforeach
                                    {{-- <li class="main-nav-list child"><a href="#">Dried Fish<span class="number">(09)</span></a></li>
                                <li class="main-nav-list child"><a href="#">Fresh Fish<span class="number">(17)</span></a></li>
                                <li class="main-nav-list child"><a href="#">Meat Alternatives<span class="number">(01)</span></a></li>
                                <li class="main-nav-list child"><a href="#">Meat<span class="number">(11)</span></a></li> --}}
                                </ul>
                            </li>
                        @endforeach


                    </ul>
                </div>
                {{-- <div class="sidebar-filter mt-50">
                    <div class="top-filter-head">Product Filters</div>
                    <div class="common-filter">
                        <div class="head">Brands</div>
                        <form action="#">
                            <ul>
                                <li class="filter-list"><input class="pixel-radio" type="radio" id="apple"
                                        name="brand"><label for="apple">Apple<span>(29)</span></label></li>

                            </ul>
                        </form>
                    </div>

                </div> --}}
            </div>
            <div class="col-xl-9 col-lg-8 col-md-7">
                <!-- Start Filter Bar -->
                <div class="filter-bar d-flex flex-wrap align-items-center">
                    {{-- <div class="sorting">
                        <select>
                            <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option>
                        </select>
                    </div>
                    <div class="sorting mr-auto">
                        <select>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                        </select>
                    </div> --}}
                    {{ $products->links('vendor.pagination.karma') }}
                </div>
                <!-- End Filter Bar -->
                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        <!-- single product -->
                        @forelse ($products as $item)
                            <div class="col-lg-4 col-md-6">
                                <div class="single-product">
                                    <img class="img-fluid" src="{{ asset('storage/products/' . $item->image) }}"
                                        alt="{{ $item->name }}">
                                    <div class="product-details">
                                        <h6>{{ $item->name }}</h6>
                                        <div class="price">
                                            <h6>{{ number_format($item->price) }}</h6>
                                            {{-- <h6 class="l-through">{{ $item->price + 100000 }}</h6> --}}
                                            <h6>Stock : {{ $item->stock }}</h6>
                                        </div>

                                        {{-- <div class="prd-bottom">
                                            <form action="{{ route('front.cart') }}" method="POST">
                                                @csrf
                                                <div class="product_count">

                                                    <input type="hidden" name="qty" value="1" title="Quantity:"
                                                        class="input-text qty">

                                                    <input type="hidden" name="product_id" value="{{ $item->id }}"
                                                        class="form-control">

                                                    <a href="{{ route('front.cart') }}" id="tekan" class="social-info"
                                                        onclick="event.preventDefault();
                                                document.getElementById('tekan').
                                                ">
                                                        <span class="ti-bag"></span>
                                                        <p class="hover-text">add to bag</p>
                                                    </a>

                                                    <a href="" class="social-info">
                                                        <span class="lnr lnr-heart"></span>
                                                        <p class="hover-text">Wishlist</p>
                                                    </a>
                                                    <a href="" class="social-info">
                                                        <span class="lnr lnr-sync"></span>
                                                        <p class="hover-text">compare</p>
                                                    </a>
                                                    <a href="{{ url('/product/' . $item->slug) }}" class="social-info">
                                                        <span class="lnr lnr-move"></span>
                                                        <p class="hover-text">view more</p>
                                                    </a>

                                                    <button id="tombol" class="social-info" style="display: none">

                                                    </button>
                                                </div>
                                            </form>
                                        </div> --}}

                                        <div class="prd-bottom">

                                            <form action="{{ route('front.cart') }}" method="POST"
                                                id="{{ $item->slug }}" style="display: none">
                                                @csrf
                                                <input type="hidden" name="qty" value="1" title="Quantity:"
                                                    class="input-text qty">
                                                <input type="hidden" name="product_id" value="{{ $item->id }}"
                                                    class="form-control">
                                            </form>

                                            <a href="{{ route('front.cart') }}" class="social-info"
                                                onclick="event.preventDefault();
                                            document.getElementById('{{ $item->slug }}').submit();">
                                                <span class="ti-bag"></span>
                                                <p class="hover-text">add to bag</p>
                                            </a>
                                            {{-- <a href="" class="social-info">
                                                <span class="lnr lnr-heart"></span>
                                                <p class="hover-text">Wishlist</p>
                                            </a>
                                            <a href="" class="social-info">
                                                <span class="lnr lnr-sync"></span>
                                                <p class="hover-text">compare</p>
                                            </a> --}}
                                            <a href="{{ url('/product/' . $item->slug) }}" class="social-info">
                                                <span class="lnr lnr-move"></span>
                                                <p class="hover-text">view more</p>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @empty
                        @endforelse
                    </div>
                </section>
                <!-- End Best Seller -->
                <!-- Start Filter Bar -->
                <div class="filter-bar d-flex flex-wrap align-items-center">
                    {{-- <div class="sorting mr-auto">
                        <select>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                        </select>
                    </div> --}}
                    {{ $products->links('vendor.pagination.karma') }}
                </div>
                <!-- End Filter Bar -->
            </div>
        </div>
    </div>
@endsection
