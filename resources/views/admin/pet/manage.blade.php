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
            <div class="col-sm-4">
                {!! Form::bsSelect('user_id', 'Owner', $customers->prepend('', '')) !!}
            </div>
            <div class="col-5">
                {!! Form::bsText('name', 'Name') !!}
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE']) !!}
            </div>
            <div class="col-3">
                {!! Form::bsSelect('pet_breed_id', 'Breed', $breeds) !!}
            </div>
            <div class="col-3">
                {!! Form::bsSelect('pet_reproductive_alteration_id', 'Reproductive Alteration', $reproductiveAlterations, $resourceData->pet_reproductive_alteration_id) !!}
            </div>
            <div class="col">
                {!! Form::bsDate('birthdate', 'Birthdate') !!}
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                {!! Form::bsText('color', 'Color') !!}
            </div>
            <!-- <div class="col-3">
                {!! Form::bsText('weight', 'Weight (kg)') !!}
            </div> -->
            <div class="col-6">
                {!! Form::bsText('physical_characteristics', 'Physical Characteristics') !!}
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-success">Save</button>

        {!! Form::close() !!}

@endsection




@push('scripts')
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('[name=gender]').change(function () {
            var val = $(this).val();

            if(!val) return;

            $('[name=pet_reproductive_alteration_id]').val('');

            var hide = '',
                show = '';
            if(val === 'MALE'){
                hide = 'FEMALE';
                show = 'MALE';
            }else if(val === 'FEMALE') {
                hide = 'MALE';
                show = 'FEMALE';
            }

            $('[name=pet_reproductive_alteration_id] optgroup[label='+show+']').removeClass('d-none');
            $('[name=pet_reproductive_alteration_id] optgroup[label='+hide+']').addClass('d-none');



        }).trigger('change');
    });
</script>
@endpush
