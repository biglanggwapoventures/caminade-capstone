@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <div class="row mt-3">
        @if($resourceList->isEmpty())
        <div class="col-5">
            <div class="alert alert-info text-center" role="alert">
                <h4 class="alert-heading"><i class="fas fa-frown fa-2x"></i></h4>
                <p class="mt-3">Uh oh! Seems like you do not have any registered pets the moment. But fret not, you can register as much pet as you want in no time!</p>
                <hr>
                <p class="mb-0">
                    Fill out the information sheet on the right to get you started!
                </p>
            </div>
        </div>
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    Pet Information Sheet
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => MyHelper::resource('store'), 'method' => 'POST']) !!}
                        <div class="row">
                            <div class="col">
                                {!! Form::bsText('name', 'Pet Name') !!}
                            </div>
                            <div class="col-4">
                                {!! Form::bsSelect('pet_breed_id', 'Breed', $breeds) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'Male', 'FEMALE' => 'Female']) !!}
                            </div>
                            <div class="col-4">
                                {!! Form::bsSelect('pet_reproductive_alteration_id', 'Reproductive Alteration', $reproductiveAlterations) !!}
                            </div>
                            <div class="col-4">
                                {!! Form::bsDate('birthdate', 'Birthdate (Optional)') !!}
                            </div>
                        </div>
                        <hr>
                        <div class="text-right">
                            <button type="submit"  class="btn btn-success">Register my pet!</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        @else
        <div class="col-3">
            <button class="btn btn-success btn-sm btn-block mb-2" data-toggle="modal" data-target="#new-pet"><i class="fas fa-plus"></i> Register new pet</button>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">My Pets</h5>
                    <p class="card-text mt-0">{{ $resourceList->count() }} pet(s) in total</p>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($resourceList as $pet)
                        <a href="#" class="list-group-item list-group-item-action">{{ "{$loop->iteration}. {$pet->name}" }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-3">Pet Information Sheet</h4>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</a>
                    <a class="nav-item nav-link" id="nav-appointment-history-tab" data-toggle="tab" href="#nav-appointment-history" role="tab" aria-controls="nav-appointment-history" aria-selected="false">Appointment History</a>
                    <a class="nav-item nav-link" id="nav-logs-tab" data-toggle="tab" href="#nav-logs" role="tab" aria-controls="nav-logs" aria-selected="false">Logs</a>
                </div>
            </nav>
            <div class="tab-content mt-4" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="row">
                        <div class="col-9">
                            {!! Form::bsText('name', 'Pet Name') !!}
                        </div>
                        <div class="col">
                            {!! Form::bsSelect('pet_breed_id', 'Breed', $breeds, null, ['class' => 'custom-select w-100']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'Male', 'FEMALE' => 'Female'], null, ['class' => 'custom-select w-100']) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::bsSelect('pet_reproductive_alteration_id', 'Reproductive Alteration', $reproductiveAlterations, null, ['class' => 'custom-select w-100']) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::bsDate('birthdate', 'Birthdate (Optional)') !!}
                        </div>
                    </div>
                    <hr>
                    <div class="text-right">
                        <button type="submit" class="btn btn-info">Save</button>

                    </div>
                </div>
                <div class="tab-pane fade" id="nav-appointment-history" role="tabpanel" aria-labelledby="nav-appointment-history-tab">history</div>
                <div class="tab-pane fade" id="nav-logs" role="tabpanel" aria-labelledby="nav-logs-tab">logs</div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="new-pet" tabindex="-1" role="dialog" aria-labelledby="register-title" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="register-title">Register new pet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['url' => MyHelper::resource('store'), 'method' => 'POST', 'class' => 'ajax']) !!}
        <div class="modal-body">
          <div class="row">
                <div class="col">
                    {!! Form::bsText('name', 'Pet Name') !!}
                </div>
                <div class="col-4">
                    {!! Form::bsSelect('pet_breed_id', 'Breed', $breeds, null, ['class' => 'custom-select w-100']) !!}
                </div>
            </div>
        <div class="row">
            <div class="col-4">
                {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'Male', 'FEMALE' => 'Female'], null, ['class' => 'custom-select w-100']) !!}
            </div>
            <div class="col-4">
                {!! Form::bsSelect('pet_reproductive_alteration_id', 'Reproductive Alteration', $reproductiveAlterations, null, ['class' => 'custom-select w-100']) !!}
            </div>
            <div class="col-4">
                {!! Form::bsDate('birthdate', 'Birthdate (Optional)') !!}
            </div>
        </div>
      </div>
        <div class="modal-footer">
            <button type="submit"  class="btn btn-success">Submit</a>
        </div>
    {!! Form::close() !!}
    </div>
  </div>
</div>
@endpush
