@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Pets</h2>
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
            <div class="col-4">

                {!! Form::bsText('name', 'Name') !!}
                {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE']) !!}
                {!! Form::bsDate('birthdate', 'Birthdate') !!}


            </div>
            <div class="col-4">
                {!! Form::bsSelect('pet_breed_id', 'Breed', $breeds) !!}
                {!! Form::bsSelect('pet_reproductive_alteration_id', 'Reproductive Alteration', $reproductiveAlterations) !!}
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>

        {!! Form::close() !!}

@endsection
