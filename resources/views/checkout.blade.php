@extends('admin.layouts.layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container">
        @include('blocks.home-header')
        <h3 class="text-center mt-3 mb-4"><i class="fas fa-paw fa-2x"></i> <br>Checkout</h3>
        <div class="container">
            {{--<div class="row">--}}
                {{--<div class="col">@json(session('debug'))</div>--}}
            {{--</div>--}}
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Your cart</span>
                    </h4>
                    <ul class="list-group mb-3">
                        @php $totalAmount = 0 @endphp
                        @foreach($products as $item)
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">{{ $item['product_name'] }}</h6>
                                    <small class="text-muted">x {{ $item['quantity'] }} unit(s)</small>
                                </div>
                                <span class="text-muted">
                                    @php
                                        $total = $item['quantity'] * $item['product_price'];
                                        $totalAmount += $total;
                                    @endphp
                                    {{ number_format($total, 2) }}
                                </span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (PHP)</span>
                            <strong>{{ number_format($totalAmount, 2) }}</strong>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">Billing address</h4>
                    {!! Form::open(['url' => route('do.checkout'), 'method' => 'POST', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                        {!! Form::bsText('billingAddress1', 'Address', null, ['placeholder' => '1234 Main St']) !!}
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                {!! Form::bsText('billingCity', 'City', null) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!! Form::bsText('billingPostcode', 'Zip Code', null) !!}
                            </div>
                        </div>

                        <h4 class="mb-3">Credit Card Information</h4>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                {!! Form::bsText('firstName', 'First Name', auth()->user()->firstname) !!}
                            </div>
                            <div class="col-md-4 mb-3">
                                {!! Form::bsText('lastName', 'Last Name', auth()->user()->lastname) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                {!! Form::bsText('number', 'Credit Card Number') !!}
                            </div>
                            <div class="col-md-4">
                                <label for="">Expiry</label>
                                <div class="form-row align-items-center">
                                    <div class="col">
                                        {!! Form::select('expiryMonth', $monthOptions, null, ['class' => 'form-control']); !!}
                                    </div>
                                    <div class="col-auto">
                                        /
                                    </div>
                                    <div class="col">
                                        {!! Form::select('expiryYear', $yearOptions, null, ['class' => 'form-control']); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                {!! Form::bsText('card_cvv', 'CVV') !!}
                            </div>
                        </div>
                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Checkout</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection