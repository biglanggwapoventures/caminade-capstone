@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>User Account</h2>
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
                {!! Form::bsText('username', 'Desired Username') !!}
                {!! Form::bsText('email', 'Email Address') !!}
                <div class="row">
                    <div class="col">
                        {!! Form::bsPassword('password', 'Desired Password') !!}
                    </div>
                    <div class="col">
                        {!! Form::bsText('password_confirmation', 'Password, Again') !!}
                    </div>
                </div>
                {!! Form::bsSelect('role', 'Role', ['' => '', 'DOCTOR' => 'Doctor', 'STAFF' => 'Staff', 'CUSTOMER' => 'Customer']) !!}
            </div>
            <div class="col-4">
                {!! Form::bsText('firstname', 'First Name') !!}
                {!! Form::bsText('lastname', 'Last Name') !!}
                <div class="row">
                    <div class="col-4">
                        {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE']) !!}
                    </div>
                    <div class="col">
                        {!! Form::bsText('contact_number', 'Contact Number') !!}
                    </div>
                </div>
            </div>
            <div class="col-4">
                {!! Form::bsTextarea('address', 'Address') !!}
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>

        {!! Form::close() !!}

@endsection
