@extends('admin.layouts.main')
@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Orders</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new order</a>
    </div>
</div>
{!! Form::open(['url' => MyHelper::resource('index'), 'method' => 'GET', 'class' => 'form-inline mb-2 mt-2']) !!}

    <div class="form-group">
    <label class="ml-1">Customer</label>
        {!! Form::text('customer_name', null, ['class' => 'form-control ml-1']) !!}
    </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">From</label>
    {!! Form::date('from', null, ['class' => 'form-control ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">To</label>
    {!! Form::date('to', null, ['class' => 'form-control ml-1', '']) !!}
  </div>
  <button type="submit" class="btn btn-danger ml-2">Search</button>
{!! Form::close() !!}
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList As $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->customer_id ? $row->customer->fullname : $row->customer_name }}</td>
                <td>{{ date_create($row->created_at)->format('m/d/Y') }}</td>
                <td>{{ number_format($row->total_amount) }}</td>
                <td>
                    @include('components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Empty table</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
