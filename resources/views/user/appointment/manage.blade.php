@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
<style type="text/css">


.table .form-group{
  margin-bottom:0px;
}

</style>
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <div class="row mt-3">
        <div class="col-3">
            <button class="btn btn-sm btn-success btn-block disabled"><i class="fas fa-plus"></i> New appointment</button>
        </div>
        <div class="col">
            <h4 class="mb-3">Appointments</h4>
            <div class="card">
                {!! Form::open(['url' => MyHelper::resource('store'), 'method' => 'post', 'class' => 'ajax']) !!}
                <div class="card-header">New appointment sheet</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            {!! Form::bsDate('parent[appointment_date]', 'Date', null, ['class' => 'form-control form-control-sm']) !!}
                        </div>
                        <div class="col-3">
                            {!! Form::bsTime('parent[appointment_time]', 'Time', null, ['class' => 'form-control form-control-sm']) !!}
                        </div>
                        <div class="col">
                            {!! Form::bsText('parent[remarks]', 'Remarks', null, ['class' => 'form-control form-control-sm']) !!}
                        </div>
                    </div>

                </div>
                <table class="table" id="appointment-services" data-service-details="{{ $serviceInfo->toJson() }}">
                        <thead>
                            <tr class="table-active table-sm">
                                <th>Pet</th>
                                <th>Service</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! Form::bsSelect('child[0][pet_id]', null, $pets, null, ['class' => 'custom-select custom-select-sm w-100', 'data-name' => 'child[idx][pet_id]']) !!}</td>
                                <td>{!! Form::bsSelect('child[0][service_id]', null, $serviceList,  null, ['class' => 'custom-select custom-select-sm  w-100 service', 'data-name' => 'child[idx][service_id]']) !!}</td>
                                <td class="service-duration clear"></td>
                                <td class="service-price clear"></td>
                                <td><button class="btn btn-sm btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="border-0"><button type="button" class="btn btn-secondary btn-sm add-line"><i class="fas fa-plus"></i> New service</button></td>
                            </tr>
                        </tfoot>
                    </table>
                <div class="card-footer clearfix">
                    <a href="{{ route('user.appointment.index') }}" class="btn btn-primary btn-sm">Cancel</a>
                    <button class="btn btn-success btn-sm float-right">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var table = $('#appointment-services'),
            services = table.data('service-details'),
            counter = 0;

        table.on('change', '.service', function () {
            var $this = $(this),
                val = $(this).val();
            if(!val) return;
            var info = services[val],
                tr = $this.closest('tr');
            tr.find('.service-duration').text(info['duration']+' minutes');
            tr.find('.service-price').text(info['price']+' php');
        });

        $('.add-line').click(function() {
            var clone = table.find('tbody tr:first').clone();
            clone.find('select')
                .attr('name', function () {
                    return $(this).data('name').replace('idx', table.find('tbody tr').length)
                })
                .val('');
            clone.find('.clear').html('')
            clone.appendTo(table.find('tbody'))
        })

        table.on('click', '.remove-line', function () {
            var tr = table.find('tbody tr');
            if(tr.length === 1){
                tr.find('select').val('')
                    .end()
                    .find('.clear').html('');
                return;
            }
            $(this).closest('tr').remove();
        })
    });
</script>
@endpush
