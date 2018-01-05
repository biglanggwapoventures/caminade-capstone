@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Products</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new product</a>
    </div>
</div>
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Code</th>
            <th>Name</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Price</th>
            <th>Stock on hand</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList as $row)
        <tr>
            <td colspan="7"></td>
            <td>
                @include('components.form.index-actions', ['id' => $row->id])
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
