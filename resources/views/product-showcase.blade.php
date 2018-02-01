@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <h3 class="text-center mt-3 mb-4"><i class="fas fa-paw fa-2x"></i> <br>Our Products</h3>
    <div class="row">
        <div class="col-sm-3">
            <div class="card">
                <div class="card-header">Search from our products</div>
                <div class="card-body p-3">
                    {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
                    {!! Form::bsText('name', 'Search by name', null, ['class' => 'form-control']) !!}
                    @foreach($categories as $id => $name)
                    <div class="checkbox" style="margin-left:20px">
                        <label>
                            {!! Form::checkbox('category[]', $id, null) !!} {{ $name }}
                        </label>
                    </div>
                    @endforeach
                    <hr>
                    <button type="submit" class="btn btn-info btn-block">Search</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            @foreach ($data->chunk(4) as $chunk)
                <div class="row">
                    @foreach ($chunk as $item)
                        <div class="col-3">
                            <div class="card border-0">
                                <div style="height: 150px;background-image: url('{{ $item->photo_src  }}');background-position: center center;background-repeat: no-repeat;background-size: cover "></div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $item->category->description }}</h6>
                                    <p class="card-text">{{ $item->description }}</p>
                                    <a class="card-link"><i class="fas fa-money-bill-alt"></i> {{ number_format($item->price, 2) }}</a>
                                    @if($item->high_stock)
                                    <a class="card-link text-success"><i class="fas fa-check"></i> In-stock</a>
                                    @else
                                    <a class="card-link text-danger"><i class="fas fa-times"></i> Low stock</a>

                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
