@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <div class="row mt-3">
        <div class="col">
            <h4>My Purchase History</h4>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Order Date</th>
                        <th>Items</th>
                        <th class="text-right">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ date_create($row->order_date)->format('m/d/Y') }}</td>
                        <td>
                            <ul class="mb-0 pl-0">
                                @foreach($row->line as $line)
                                    <li>{{ number_format($line->quantity) }} {{ $line->product->name }} @ {{ number_format($line->product->price, 2) }} each</li>
                                @endforeach
                            </ul>
                        </td>
                        <td  class="text-right">{{ number_format($row->total_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">No data to show</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
