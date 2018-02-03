@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <h3 class="text-center mt-3 mb-4"><i class="fas fa-paw fa-2x"></i> <br>Our Services</h3>
    <div class="row">
    @foreach ($data->chunk(3) as $chunk)

            @foreach ($chunk as $item)
                <div class="col-sm-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-info">{{ $item->name }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                        </div>
                        <div class="card-footer">
                            <a class="card-link"><i class="fas fa-money-bill-alt"></i> {{ number_format($item->price, 2) }}</a>
                            <a class="card-link"><i class="fas fa-clock"></i> {{ number_format($item->duration) }} minutes</a>
                        </div>
                    </div>
                </div>
            @endforeach

    @endforeach
    </div>
</div>
@endsection
