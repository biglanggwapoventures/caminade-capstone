@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <h3 class="text-center mt-3 mb-4"><i class="fas fa-paw fa-2x"></i> <br>Our Services</h3>
    @foreach ($data->chunk(4) as $chunk)
        <div class="row">
            @foreach ($chunk as $item)
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                            <a class="card-link"><i class="fas fa-money-bill-alt"></i> {{ number_format($item->price, 2) }}</a>
                            <a class="card-link"><i class="fas fa-clock"></i> {{ number_format($item->duration) }} minutes</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
