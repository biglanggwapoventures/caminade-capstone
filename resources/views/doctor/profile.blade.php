@extends('admin.layouts.main')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
<style type="text/css">
    .table .form-group{
        margin-bottom:0;
    }
</style>
@endpush

@section('body')
<div class="row mt-4 align-items-center">
    <div class="col">
        <h2>My Profile</h2>
    </div>
</div>
{!! Form::model(Auth::user(), ['url' => route('doctor.profile.update'), 'method' => 'PATCH','class' => 'ajax']) !!}
<div class="row mt-3">
    <div class="col-sm-5">
        <div class="row">
            <div class="col-sm-6">
                {!! Form::bsText('username', 'Username', null, ['readonly' => true, 'class' => 'form-control form-control-plaintext']) !!}
            </div>
            <div class="col-sm-6">
                {!! Form::bsText('email', 'Email Address') !!}
            </div>
        </div>
        <div class="row">
             <div class="col-sm-6">
                {!! Form::bsText('firstname', 'First Name') !!}
            </div>
            <div class="col-sm-6">
                {!! Form::bsText('lastname', 'Last Name') !!}
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE'], null, ['class' => 'custom-select w-100']) !!}
            </div>
            <div class="col-8">
                {!! Form::bsText('contact_number', 'Contact Number') !!}
            </div>
        </div>
         {!! Form::bsTextarea('address', 'Address', null, ['rows' => 3]) !!}
         <div class="row">
             <div class="col">
                 {!! Form::bsPassword('password', 'Password') !!}
             </div>
             <div class="col">
                 {!! Form::bsPassword('password_confirmaton', 'Confirm Password') !!}
             </div>
         </div>
    </div>
    <div class="col-sm-7 ">
        <div class="row">
            <div class="col-sm-4">
                {!! Form::bsFile('profile[photo_filepath]', 'Photo') !!}
            </div>
            <div class="col-sm-8">
                {!! Form::bsText('profile[specialization]', 'Specialization', $doctorProfile->specialization) !!}
            </div>
        </div>
        {!! Form::bsTextarea('profile[bio]', 'Bio', $doctorProfile->bio, ['rows' => 3]) !!}
        <!-- {{ gettype( $doctorProfile->schedule) }} -->
        <table class="table table-sm">
            <thead>
                <tr><th class="bg-dark text-white">Day</th><th class="bg-dark text-white">Time in</th><th class="bg-dark text-white">Time out</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Monday</td>
                    <td>{!! Form::bsTime('profile[schedule][mon][in]', null, $doctorProfile->getDutyHour('in', 'mon')) !!}</td>
                    <td>{!! Form::bsTime('profile[schedule][mon][out]', null, $doctorProfile->getDutyHour('out', 'mon')) !!}</td>
                </tr>
                <tr>
                    <td>Tuesday</td>
                    <td>{!! Form::bsTime('profile[schedule][tue][in]', null, $doctorProfile->getDutyHour('in', 'tue')) !!}</td>
                    <td>{!! Form::bsTime('profile[schedule][tue][out]', null, $doctorProfile->getDutyHour('out', 'tue')) !!}</td>
                </tr>
                <tr>
                    <td>Wednesday</td>
                    <td>{!! Form::bsTime('profile[schedule][wed][in]', null, $doctorProfile->getDutyHour('in', 'wed')) !!}</td>
                    <td>{!! Form::bsTime('profile[schedule][wed][out]', null, $doctorProfile->getDutyHour('out', 'wed')) !!}</td>
                </tr>
                <tr>
                    <td>Thursday</td>
                    <td>{!! Form::bsTime('profile[schedule][thu][in]', null, $doctorProfile->getDutyHour('in', 'thu')) !!}</td>
                    <td>{!! Form::bsTime('profile[schedule][thu][out]', null, $doctorProfile->getDutyHour('out', 'thu')) !!}</td>
                </tr>
                <tr>
                    <td>Friday</td>
                    <td>{!! Form::bsTime('profile[schedule][fri][in]', null, $doctorProfile->getDutyHour('in', 'fri')) !!}</td>
                    <td>{!! Form::bsTime('profile[schedule][fri][out]', null, $doctorProfile->getDutyHour('out', 'fri')) !!}</td>
                </tr>
                <tr>
                    <td>Saturday</td>
                    <td>{!! Form::bsTime('profile[schedule][sat][in]', null, $doctorProfile->getDutyHour('in', 'sat')) !!}</td>
                    <td>{!! Form::bsTime('profile[schedule][sat][out]', null, $doctorProfile->getDutyHour('out', 'sat')) !!}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<hr>
<button type="submit" class="btn btn-success">Save profile</button>
{!! Form::close() !!}
@endsection
