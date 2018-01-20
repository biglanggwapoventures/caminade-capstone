@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Product</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('index') }}">Back to list</a>
    </div>
</div>
<div class="row">
    <div class="col">
        <hr>
    </div>
</div>

        @if(is_null($resourceData->id))
        {!! Form::open(['url' => MyHelper::resource('store'), 'method' => 'POST', 'files' => true]) !!}
        @else
        {!! Form::model($resourceData, ['url' => MyHelper::resource('update', ['id' => $resourceData->id]), 'method' => 'PATCH',  'files' => true]) !!}
        @endif

        <div class="row">
            <div class="col-sm-3">
                {!! Form::bsSelect('product_category_id', 'Category', $categories) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::bsText('code', 'Code') !!}
            </div>
            <div class="col">
                {!! Form::bsText('name', 'Name') !!}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-3">
                {!! Form::bsSelect('supplier_id', 'Supplier', $suppliers) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::bsText('price', 'Price') !!}
            </div>
            <div class="col-sm-3">
                {!! Form::bsText('reorder_level', 'Reorder Level') !!}
            </div>
            <div class="col-sm-3">
                @if(is_null($resourceData->id))
                    {!! Form::bsText('stock', 'Beginning Stock') !!}
                @else
                <div class="form-group">
                        <label for=>Beginning Stock</label>
                        <p class="form-control-static">{{ $resourceData->stock }}</p>
                    </div>
                @endif
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-3">
                {!! Form::bsFile('photo', 'Photo') !!}
                @if($errors->has('photo'))
                    <p class="bg-danger text-white">{{ $errors->first('photo') }}</p>
                @endif
                <div class="bg-info rounded p-3">
                    {!! Form::bsSelect('product_status', 'Status', ['' => '', 'active' => 'Active', 'inactive' => 'Inactive']) !!}
                </div>

            </div>
            <div class="col">
                {!! Form::bsTextarea('description', 'Description', null, ['rows' => 4]) !!}
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-success">Save</button>

        {!! Form::close() !!}

@endsection
