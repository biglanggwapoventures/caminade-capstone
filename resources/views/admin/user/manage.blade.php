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
            <div class="col-sm-3">
                {!! Form::bsText('username', 'Desired Username') !!}
            </div>
            <div class="col-sm-3">
                {!! Form::bsText('email', 'Email Address') !!}
            </div>
            <div class="col-sm-2">
                {!! Form::bsSelect('role', 'Role', ['' => '', 'DOCTOR' => 'Doctor', 'STAFF' => 'Staff', 'CUSTOMER' => 'Customer'], null, ['class' => 'custom-select w-100']) !!}
            </div>
            <div class="col">
                {!! Form::bsText('role_title', 'Position / Title', null, ['placeholder' => 'e.g "Receptionist", "Certified Veterinarian"']) !!}
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-3">
                {!! Form::bsText('firstname', 'First Name') !!}
            </div>
            <div class="col-sm-3">
                {!! Form::bsText('lastname', 'Last Name') !!}
            </div>
            <div class="col-sm-2">
                {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE'], null, ['class' => 'custom-select w-100']) !!}
            </div>
            <div class="col">
                {!! Form::bsText('contact_number', 'Contact Number') !!}
            </div>
        </div>
        {!! Form::bsText('address', 'Address') !!}
        <hr>

        <div class="row">
            <div class="col-sm-4">
                {!! Form::bsPassword('password', 'Desired Password') !!}
            </div>
            <div class="col-sm-4">
                {!! Form::bsPassword('password_confirmation', 'Confirm Password') !!}
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-success">Save</button>

        {!! Form::close() !!}

@endsection


@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('[name=role]').change(function () {
                var role = ['DOCTOR', 'STAFF'];
                if(role.indexOf($(this).val()) === -1){
                    $('[name=role_title]').closest('.form-group').addClass('d-none');
                    return;
                }
                $('[name=role_title]').closest('.form-group').removeClass('d-none');
            }).trigger('change')
        });
    </script>
@endpush
