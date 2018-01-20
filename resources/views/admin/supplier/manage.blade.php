@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Products Suppliers </h2>
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
<div class="row">
    <div class="col-4">
        @if(is_null($resourceData->id))
        {!! Form::open(['url' => MyHelper::resource('store'), 'method' => 'POST']) !!}
        @else
        {!! Form::model($resourceData, ['url' => MyHelper::resource('update', ['id' => $resourceData->id]), 'method' => 'PATCH']) !!}
        @endif

            {!! Form::bsText('description', 'Supplier Description') !!}
            <button type="submit" class="btn btn-success">Save</button>

        {!! Form::close() !!}
    </div>
</div>
@endsection
