@extends('admin.layouts.layout')
@section('content')
    <div class="container">
    @include('blocks.home-header')
    <div class="row">
      <div class="col-sm-4 offset-sm-4">
            <div class="card card-body mt-5">
                <h4 class="card-title">
                    Verify your account
                </h4>
                @if(session('err'))
                    <div class="alert alert-danger">{{ session('err') }}</div>
                @endif
                {!! Form::open(['url' => route('account.do.verify'), 'method' => 'POST']) !!}
                    {!! Form::bsText('code','Your code') !!}
                    <button type="submit" class="btn btn-success btn-block btn-lg">Submit</button>
                {!! Form::close() !!}
            </div>
      </div>
    </div>
    </div>
@endsection
