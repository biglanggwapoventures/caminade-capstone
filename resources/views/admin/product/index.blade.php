@extends('admin.layouts.main')

@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Products</h2>
    </div>
    @if(auth()->user()->is('admin'))
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new product</a>
    </div>
    @endif
</div>
{!! Form::open(['url' => MyHelper::resource('index'), 'method' => 'GET', 'class' => 'form-inline mb-2 mt-2']) !!}

    <div class="form-group">
        <label class="mr-1">Name</label>
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="ml-1">Category</label>
        {!! Form::select('category', $categories, null, ['class' => 'custom-select ml-1']) !!}
    </div>
    <div class="form-group">
        <label class="ml-1">Supplier</label>
        {!! Form::select('supplier', $suppliers, null, ['class' => 'custom-select ml-1']) !!}
    </div>
    <div class="form-group">
        <label class="ml-1">Status</label>
        {!! Form::select('status', ['' => '** ALL **',  'active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'custom-select ml-1']) !!}
    </div>
    <div class="form-group">
        <label class="ml-1">Sort by</label>
        {!! Form::select('sort_by', ['' => 'None',  'stock_desc' => 'Stock High to Low', 'stock_asc' => 'Stock Low to High'], null, ['class' => 'custom-select ml-1']) !!}
    </div>
  <button type="submit" class="btn btn-danger ml-2">Search</button>
{!! Form::close() !!}
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Image</th>
            <th>Code</th>
            <th>Name</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Price</th>
            <th>Stock on hand</th>
            <th>Reorder Level</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList as $row)
        <tr>
            <td><img src="{{ $row->photo_src }}" alt="" style="width:100px;height: 100px"></td>
            <td>{{ $row->code }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->category->description }}</td>
            <td>{{ $row->supplier->description }}</td>
            <td>{{ number_format($row->price, 2) }}</td>
            <td>
                {{ number_format($row->stock_on_hand) }} <br>
                 @if($row->stock_on_hand <= $row->reorder_level)
                    <span class="text-danger"><i class="fas fa-arrow-down"></i> LOW</span>
                @else
                    <span class="text-success"><i class="fas fa-arrow-up"></i> HIGH</span>
                @endif
                <br>
            </td>
            <td>
                {{ $row->reorder_level }}
            </td>
            <td>
                @if($row->product_status === 'inactive')
                    <span class="badge badge-warning badge-pill">INACTIVE</span>
                @else
                    <span class="badge badge-success badge-pill">ACTIVE</span>
                @endif
            </td>
            <td>
                 @includeWhen(Auth::user()->is('admin'), 'components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])
                <a href="{{ route('admin.product.logs', ['product' => $row->id]) }}" class="btn btn-info btn-sm">Logs</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-danger text-center">Emtpty table</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
