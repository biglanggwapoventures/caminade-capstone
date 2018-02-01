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
                {!! Form::number('quantity', null, ['class' => 'form-control ml-1']) !!}
            </div>

            <button type="submit" class="btn btn-success ml-2">Submit</button>
        {!! Form::close() !!}
        <table class="table table-sm table-hover mt-2">
            <thead>
                <th class="bg-primary text-white">Usage</th>
                <th class="bg-primary text-white">Quantity</th>
                <th class="bg-primary text-white">Stock on hand</th>
            </thead>
            <tbody>
                @foreach($product->logs as $log)
                    <tr>
                        <td>{{ $log->remarks }}</td>
                        <td>
                            @if($log->quantity > 0)
                            <span class="text-success">
                            @else
                            <span class="text-danger">
                            @endif
                            {{ number_format($log->quantity) }}
                            </span>
                        </td>
                        <td>{{ number_format($log->balance) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col">

    </div>
</div>
@endsection
