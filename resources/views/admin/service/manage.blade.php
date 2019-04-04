@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Services</h2>
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
        {!! Form::open(['url' => MyHelper::resource('store'), 'method' => 'POST']) !!}
        @else
        {!! Form::model($resourceData, ['url' => MyHelper::resource('update', ['id' => $resourceData->id]), 'method' => 'PATCH']) !!}
        @endif

            <div class="row">
                <div class="col-sm-5">
                    {!! Form::bsText('name', 'Name') !!}
                </div>
                <div class="col-sm-2">
                    {!! Form::bsText('duration', 'Duration (in minutes)') !!}
                </div>
                <div class="col-sm-2">
                    {!! Form::bsText('price', 'Price') !!}
                </div>
            </div>
             {!! Form::bsTextarea('description', 'Description', null, ['rows' =>  3]) !!}

            @if($resourceData->id)
            <div class="row">
                <div class="col-3">
                    <div class="bg-info rounded p-3">
                        {!! Form::bsSelect('service_status', 'Status', ['' => '', 'active' => 'Active', 'inactive' => 'Inactive']) !!}
                    </div>
                </div>
            </div>
            @endif


            <hr>
            <button type="submit" class="btn btn-success">Save</button>

        {!! Form::close() !!}
@endsection
