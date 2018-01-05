@extends('admin.layouts.layout')

@section('content')
<div class="container" style="height:100%">
    <div class="d-flex row justify-content-center align-items-center" style="height:100%">
        <div class="p-2 col-sm-12 col-md-5 col-xs-12">

            <div class="card text-white bg-success">
                <div class="card-header">
                    Pet Care Admin
                </div>
                <div class="card-body">
                    @if($errors->count())
                        <div class="alert alert-dismissible alert-info">
                            <ul class="list-unstyled mb-0">
                                <li>{!! implode('</li><li>', $errors->all()) !!}</li>
                            </ul>
                        </div>
                    @endif
                    {!! Form::open(['url' => route('admin.do.login'), 'method' => 'POST']) !!}
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                {!! Form::text('username', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                {!! Form::password('password', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit"  class="btn btn-primary">Login</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
