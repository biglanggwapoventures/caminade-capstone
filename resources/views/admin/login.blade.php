@extends('admin.layouts.layout')

@section('content')
<div class="container" style="height:100%">
    <div class="d-flex row justify-content-center align-items-center" style="height:100%">
        <div class="p-2 col-sm-12 col-md-5 col-xs-12">

            <div class="card">
                <div class="card-header text-center">
                    Pet Care Admin | Login
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => route('admin.do.login'), 'method' => 'POST']) !!}
                        {!! Form::bsText('username', 'Username') !!}
                        {!! Form::bsPassword('password', 'Password') !!}
                        <hr>
                        <div class="text-right">
                            <button type="submit"  class="btn btn-primary btn-block">Login</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
