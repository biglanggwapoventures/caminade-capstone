@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
<style type="text/css">


.table .form-group{
  margin-bottom:0px;
}

</style>
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <div class="row mt-3">
        <div class="col-3">
            <button class="btn btn-success btn-block disabled"><i class="fas fa-plus"></i> New appointment</button>
        </div>
        <div class="col">
            <h4 class="mb-3">Appointments</h4>
            <div class="card">
                <div class="card-header">View appointment ref #  {{ $resourceData->id }}</div>
                <div class="card-body">
                    @if($resourceData->is('approved') || $resourceData->is('completed'))
                        <div class="alert alert-success p-2">
                            <p class="mb-1">This appointment has been approved!</p>
                            <p class="mb-0">
                                Assigned Doctor:
                                <strong>{{ $resourceData->doctor->fullname }}</strong>
                            </p>
                        </div>
                    @elseif($resourceData->is('denied'))
                        <div class="alert alert-danger p-2">
                            <p class="mb-1">This appointment has been denied due to the following reason:</p>
                            <p class="mb-0"><strong>{{ $resourceData->status_remarks }}</strong></p>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-3">
                            {!! Form::bsDate('', 'Date', $resourceData->appointment_date, ['class' => 'form-control', 'readonly' => true]) !!}
                        </div>
                        <div class="col-3">
                            {!! Form::bsTime('', 'Time', $resourceData->appointment_time, ['class' => 'form-control', 'readonly' => true]) !!}
                        </div>
                        <div class="col">
                            {!! Form::bsText('', 'Remarks', $resourceData->remarks, ['class' => 'form-control', 'readonly' => true]) !!}
                        </div>
                    </div>

                </div>
                <table class="table table-sm">
                    <thead>
                        <tr class="table-active">
                            <th class="bg-info text-white">Service</th>
                            <th class="bg-info text-white">Pet</th>
                            <th class="bg-info text-white">Duration</th>
                            <th class="bg-info text-white">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resourceData->line AS $line)
                        <tr>
                            <td>
                                 {{ $line->service->name }}
                            </td>
                            <td>
                                {{ "{$line->pet->name} ({$line->pet->breed->description})" }}
                            </td>
                            <td class="service-duration clear">{{ $line->service->duration }} minutes</td>
                            <td class="service-price clear">{{ number_format($line->service->price, 2) }} php</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right"></td>
                            <td class="text-success"><strong>{{ number_format($resourceData->calculateTotalServiceAmount(), 2) }} php</strong></td>
                        </tr>
                    </tbody>
                </table>
                @if($resourceData->usedProducts->isNotEmpty())
                <table class="table table-sm">
                    <thead>
                        <tr class="table-active">
                            <th class="bg-info text-white">Product</th>
                            <th class="bg-info text-white">Quantity</th>
                            <th class="bg-info text-white">Price</th>
                            <th class="bg-info text-white">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resourceData->usedProducts AS $used)
                        <tr>
                            <td>{{ $used->product->name }}</td>
                            <td>{{ $used->quantity }}</td>
                            <td>{{ $used->product->price }} php</td>
                            <td>{{ number_format($used->product->price * $used->quantity, 2) }} php</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right"></td>
                            <td class="text-success"><strong>{{ number_format($resourceData->calculateTotalProductAmount(), 2) }} php</strong></td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-body">
                    <p class="text-center">
                        TOTAL: {{ number_format($resourceData->getTotalAmount(), 2) }}
                    </p>
                </div>
                @endif
                <div class="card-footer clearfix">
                    <a href="{{ route('user.appointment.index') }}" class="btn btn-primary">Go back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
