@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <h3 class="text-center mt-3 mb-4"><i class="fas fa-paw fa-2x"></i> <br>Our Products</h3>
    @foreach ($data->chunk(4) as $chunk)
        <div class="row">
            @foreach ($chunk as $item)
                <div class="col-3">
                    <div class="card border-0">
                        <img class="card-img-top w-75 m-auto" src="{{ $item->photo_src }}" alt="Card image cap" >
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $item->category->description }}</h6>
                            <p class="card-text">{{ $item->description }}</p>
                            <a class="card-link"><i class="fas fa-money-bill-alt"></i> {{ number_format($item->price, 2) }}</a>
                            <a class="card-link text-success"><i class="fas fa-check"></i> In-stock</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
