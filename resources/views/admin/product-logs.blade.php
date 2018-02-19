@extends('admin.layouts.main')


@section('body')
<div class="row mt-4 align-items-center">
    <div class="col">
        <h2>{{ $product->name }} Logs</h2>
    </div>
    <div class="col text-right">
        <a href="{{ route('admin.product.index') }}" class="btn btn-info">Back to list</a>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 offset-sm-2">
        {!! Form::open(['url' => route('admin.product.logs.adjust', ['product' => $product->id]), 'class' => 'form-inline']) !!}
             <div class="form-group">
                <label class="control-label">Adjust Quantity</label>
                {!! Form::select('action', ['add' => 'Add', 'subtract' => 'Subtract'], null, ['class' => 'custom-select ml-1']) !!}
            </div>
            <div class="form-group">
                {!! Form::number('quantity', null, ['class' => 'form-control ml-1', 'min' => 1]) !!}
            </div>
            <button type="submit" class="btn btn-success ml-2">Submit</button>
        {!! Form::close() !!}
        <table class="table table-sm table-hover mt-2">
            <thead>
                <th class="bg-primary text-white">Date</th>
                <th class="bg-primary text-white">Description</th>
                <th class="bg-primary text-white">Usage</th>
                <th class="bg-primary text-white">Replenish</th>
                <th class="bg-primary text-white">Balance</th>
            </thead>
            <tbody>
                @foreach($product->logs as $log)
                    <tr>
                        <td>{{ date_create($log->created_at)->format('F d, Y') }}</td>
                        <td>{{ $log->remarks }}</td>
                        <td class="text-danger text-right">{{ $log->quantity < 0 ? number_format($log->quantity * -1) : '' }}</td>
                        <td class="text-success text-right">{{ $log->quantity > 0 ? number_format($log->quantity) : '' }}</td>
                        <td class="text-right">{{ number_format($log->balance) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col">

    </div>
</div>
@endsection
