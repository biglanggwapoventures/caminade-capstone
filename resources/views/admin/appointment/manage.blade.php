@extends('admin.layouts.main')
@push('css')
@endpush
<style type="text/css">


.table .form-group{
  margin-bottom:0px;
}

</style>

@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Appointments</h2>
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
<div class="row">
    <div class="col-12">
        @if(is_null($resourceData->id))
        {!! Form::open(['url' => MyHelper::resource('store'), 'method' => 'POST', 'class' => 'ajax','data-next-url' => MyHelper::resource('index')]) !!}
        @else
        {!! Form::model($resourceData, ['url' => MyHelper::resource('update', ['id' => $resourceData->id]), 'method' => 'PATCH', 'class' => 'ajax', 'data-next-url' => MyHelper::resource('index')]) !!}
        @endif

            <div class="row">
                <div class="col-3">
                    {!! Form::bsSelect('parent[customer_id]', 'Customer', $customerList, $resourceData->customer_id, ['class' => 'custom-select w-100', 'data-get-pets-url' => route('api:get-customer-pets', ['customerId' => '__ID__']), 'id' => 'customer']) !!}
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col">
                             {!! Form::bsDate('parent[appointment_date]', 'Date', $resourceData->appointment_date, ['min' => date('Y-m-d')]) !!}
                        </div>
                        <div class="col">
                            {!! Form::bsSelect('parent[appointment_time]', 'Time', MyHelper::timeInterval(date_create_from_format('H:i', '09:00'), date_create_from_format('H:i', '17:00')), $resourceData->appointment_time) !!}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    {!! Form::bsSelect('parent[doctor_id]', 'Assigned Doctor', $doctorList, $resourceData->doctor_id, ['class' => 'custom-select w-100']) !!}
                </div>
            </div>
            {!! Form::bsTextarea('parent[remarks]', 'Remarks', $resourceData->remarks, ['rows' => 3]) !!}
            <h4 class="mt-5">Services Rendered</h4>
            {{-- @php
                echo '<pre>';
                print_r($resourceData->toArray());
            @endphp --}}
            <div class="card">
                <table class="table dynamic mb-0" id="service-table"  data-service-details="{{ $serviceInfo->toJson() }}">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white">Pet</th>
                            <th class="bg-secondary text-white">Service</th>
                            <th class="bg-secondary text-white">Duration</th>
                            <th class="bg-secondary text-white">Amount</th>
                            <th class="bg-secondary text-white"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(is_null($resourceData->id))
                        <tr>
                            <td>{!! Form::bsSelect('child[0][pet_id]', null, [], null, ['class' => 'pets custom-select  w-100', 'data-name' => 'child[idx][pet_id]']) !!}</td>
                            <td>
                                {!! Form::bsSelect('child[0][service_id]', null, $serviceList,  null, ['class' => 'custom-select w-100 service', 'data-name' => 'child[idx][service_id]']) !!}
                                {!! Form::hidden("child[0][service_price]",null, ['class' => 'hidden-service-price', 'data-name' => 'child[idx][service_price]']) !!}
                                {!! Form::hidden("child[0][service_duration]", null, ['class' => 'hidden-service-duration', 'data-name' => 'child[idx][service_duration]']) !!}
                            </td>
                            <td class="service-duration clear"></td>
                            <td class="service-price clear"></td>
                            <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                        </tr>
                        @else
                            @foreach($resourceData->line AS $row)
                            <tr>
                                <td>
                                    {!! Form::bsSelect("child[{$loop->index}][pet_id]", null, $customerPets, $row->pet_id, ['class' => 'pets custom-select  w-100', 'data-name' => 'child[idx][pet_id]']) !!}
                                    {!! Form::hidden("child[{$loop->index}][id]", $row->id) !!}
                                </td>
                                <td>
                                    {!! Form::bsSelect("child[{$loop->index}][service_id]", null, $serviceList,  $row->service_id, ['class' => 'custom-select w-100 service', 'data-name' => 'child[idx][service_id]']) !!}
                                    {!! Form::hidden("child[{$loop->index}][service_price]", $row->service_price, ['class' => 'hidden-service-price', 'data-name' => 'child[idx][service_price]']) !!}
                                    {!! Form::hidden("child[{$loop->index}][service_duration]", $row->service_duration, ['class' => 'hidden-service-duration', 'data-name' => 'child[idx][service_duration]']) !!}
                                </td>
                                <td class="service-duration clear"></td>
                                <td class="service-price clear"></td>
                                <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><button type="button" class="btn btn-secondary add-line"><i class="fas fa-plus"></i> New service</button></td>
                            <td colspan="2" class="text-right">Total</td>
                            <td id="service-total"> </td>
                            <td></td>
                        </tr>
                    </tfoot>
            </table>
            </div>
            <h4 class="mt-5">Products Used (Optional)</h4>
            <div class="card">
                <table class="table dynamic mb-0" id="product-table"  data-product-details="{{ $productInfo->toJson() }}">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white">Product</th>
                            <th class="bg-secondary text-white">Quantity</th>
                            <th class="bg-secondary text-white">Price</th>
                            <th class="bg-secondary text-white">Amount</th>
                            <th class="bg-secondary text-white"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($resourceData->usedProducts->isEmpty())
                        <tr>
                            <td>{!! Form::bsSelect('products[0][product_id]', null, $productList, null, ['class' => 'product custom-select  w-100', 'data-name' => 'products[idx][product_id]']) !!}</td>
                            <td>
                                {!! Form::bsText('products[0][quantity]', null, null, ['data-name' => 'products[idx][quantity]', 'class' => 'form-control quantity']) !!}
                                {!! Form::hidden("products[0][unit_price]", null, ['data-name' => 'products[idx][unit_price]', 'class' => 'form-control unit-price']) !!}
                            </td>
                            <td class="product-price clear"></td>
                            <td class="amount clear"></td>
                            <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                        </tr>
                        @else
                            @foreach($resourceData->usedProducts as $row)
                            <tr>
                                <td>
                                    {!! Form::bsSelect("products[{$loop->index}][product_id]", null, $productList, $row->product_id, ['class' => 'product custom-select  w-100', 'data-name' => 'products[idx][product_id]']) !!}
                                    {!! Form::hidden("products[{$loop->index}][id]", $row->id) !!}
                                </td>
                                <td>
                                    {!! Form::bsText("products[{$loop->index}][quantity]", null, $row->quantity, ['data-name' => 'products[idx][quantity]', 'class' => 'form-control quantity']) !!}
                                    {!! Form::hidden("products[{$loop->index}][unit_price]", $row->unit_price, ['data-name' => 'products[idx][unit_price]', 'class' => 'form-control unit-price']) !!}
                                </td>
                                <td class="product-price clear"></td>
                                <td class="amount clear"></td>
                                <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        @endif

                    </tbody>
                    <tfoot>
                         <td ><button type="button" class="btn btn-secondary  add-line"><i class="fas fa-plus"></i> New product</button></td>
                            <td colspan="2" class="text-right">Total</td>
                            <td id="product-total"> </td>
                            <td></td>
                    </tfoot>
                </table>
            </div><!--
            <h4 class="mt-5">Pet Logs</h4>
            <div class="card">
                <table class="table dynamic mb-0" id="service-table"  data-service-details="{{ $serviceInfo->toJson() }}">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white">Pet</th>
                            <th class="bg-secondary text-white">Date</th>
                            <th class="bg-secondary text-white">Time</th>
                            <th class="bg-secondary text-white">Content</th>
                            <th class="bg-secondary text-white"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resourceData->petLogs as $row)
                        <tr>
                            <td>
                                    {!! Form::bsSelect("pet_logs[{$loop->index}][pet_id]", null, $customerPets ?? [],  $row->pet_id, ['class' => 'custom-select w-100 pets', 'data-name' => 'pet_logs[idx][pet_id]']) !!}
                                </td>
                                <td>
                                    {!! Form::bsDate("pet_logs[{$loop->index}][log_date]", null, $row->log_date, ['class' => 'form-control', 'data-name' => 'pet_logs[idx][log_date]']) !!}
                                </td>
                                <td>
                                    {!! Form::bsTime("pet_logs[{$loop->index}][log_time]", null, $row->log_time, ['class' => 'form-control', 'data-name' => 'pet_logs[idx][log_time]']) !!}
                                </td>
                                <td>
                                      {!! Form::bsText("pet_logs[{$loop->index}][remarks]", null, $row->remarks, ['data-name' => 'pet_logs[idx][remarks]']) !!}
                                </td>
                            <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                        </tr>
                        @empty
                            <tr>
                                <td>
                                    {!! Form::bsSelect('pet_logs[0][pet_id]', null, $customerPets ?? [],  null, ['class' => 'custom-select w-100 pets', 'data-name' => 'pet_logs[idx][pet_id]']) !!}
                                </td>
                                <td>
                                    {!! Form::bsDate('pet_logs[0][log_date]', null, null, ['class' => 'form-control', 'data-name' => 'pet_logs[idx][log_date]']) !!}
                                </td>
                                <td>
                                    {!! Form::bsTime('pet_logs[0][log_time]', null, null, ['class' => 'form-control', 'data-name' => 'pet_logs[idx][log_time]']) !!}
                                </td>
                                <td>
                                      {!! Form::bsText('pet_logs[0][remarks]', null, null,['data-name' => 'pet_logs[idx][remarks]']) !!}
                                </td>
                                <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"><button type="button" class="btn btn-secondary add-line"><i class="fas fa-plus"></i> New log</button></td>
                        </tr>
                    </tfoot>
                </table>
            </div> -->
            <h4 class="mt-5">Post Appointment Findings</h4>
            <div class="card">
                <table class="table dynamic mb-0" id="service-table"  data-service-details="{{ $serviceInfo->toJson() }}">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white">Pet</th>
                            <th class="bg-secondary text-white">Findings</th>
                            <th class="bg-secondary text-white"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resourceData->findings as $row)
                        <tr>
                            <td>
                                {!! Form::bsSelect("findings[{$loop->index}][pet_id]", null, $customerPets, $row->pet_id, ['class' => 'pets custom-select  w-100', 'data-name' => 'findings[idx][pet_id]']) !!}
                                {!! Form::hidden("findings[{$loop->index}][id]", $row->id) !!}
                            </td>
                            <td>
                                {!! Form::bsText("findings[{$loop->index}][findings]", null, $row->findings, ['data-name' => 'findings[idx][findings]']) !!}
                            </td>
                            <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                        </tr>
                        @empty
                            <tr>
                                <td>
                                    {!! Form::bsSelect('findings[0][pet_id]', null, $customerPets ?? [],  null, ['class' => 'custom-select w-100 pets', 'data-name' => 'findings[idx][pet_id]']) !!}
                                </td>
                                <td>
                                    {!! Form::bsText('findings[0][findings]', null, null,['data-name' => 'findings[idx][findings]']) !!}
                                </td>
                                <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><button type="button" class="btn btn-secondary add-line"><i class="fas fa-plus"></i> New line</button></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div class="row">
                <div class="col-4">
                    {!! Form::bsSelect('parent[appointment_status]', 'Set appointment status', $statusOptions, $resourceData->appointment_status,['class' => 'custom-select w-100']) !!}
                </div>
                <div class="col">
                    {!! Form::bsText('parent[status_remarks]', 'Status Remarks', $resourceData->status_remarks) !!}
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-success">Save</button>
        {!! Form::close() !!}
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var services = $('#service-table').data('service-details'),
            products = $('#product-table').data('product-details');

        function getServiceTotal() {
            var total = 0;
            $('#service-table tbody tr').each(function () {
                var $this = $(this),
                    service = $this.find('.service').val() ;
                if(!service) return;
                var serviceInfo = services[service],
                    service_price = $this.find('.hidden-service-price').val();
                total += (parseFloat(service_price) || 0)
            })
            return total;
        }

        function getProducTotal() {
             var total = 0;
            $('#product-table tbody tr').each(function () {
                var $this = $(this),
                    product = $this.find('.product').val() ;
                if(!product) return;
                var productInfo = products[product],
                    quantity = parseFloat($this.find('.quantity').val())
                    unit_price = parseFloat($this.find('.unit-price').val())
                    total +=  (unit_price * quantity || 0)
            })
            return total;
        }

        $('#service-table').on('change', '.service', function () {
            var $this = $(this),
                val = $(this).val();
            if(!val) return;
            var info = services[val],
                tr = $this.closest('tr');
            tr.find('.service-duration').text(tr.find('.hidden-service-duration').val()+' minutes');
            tr.find('.service-price').text(tr.find('.hidden-service-price').val()+' php');
            $('#service-table').trigger('table:changed');
        });
        $('.service').trigger('change');

        $('#service-table').on('change', '.service', function () {
            var $this = $(this),
                val = $(this).val(),
                tr = $this.closest('tr');
            if(!val) return;
            var info = services[val];
            tr.find('.service-duration').text(info['price']+' minutes');
            tr.find('.service-price').text(info['price']+' php');
            tr.find('.hidden-service-duration').val(info['duration']);
            tr.find('.hidden-service-price').val(info['price']);
            $('#service-table').trigger('table:changed');
        });

         $('#product-table').on('change', '.product', function () {
            var $this = $(this),
                tr = $this.closest('tr'),
                product = tr.find('.product').val();
            if(!product) return;
            var productInfo = products[product],
                amount = parseFloat(productInfo['price']) * parseFloat(tr.find('.quantity').val() || 0),
                unit_price = parseFloat(tr.find('.unit-price').val())
            tr.find('.amount').text(amount.toFixed(2));
            tr.find('.product-price').text(unit_price.toFixed(2));
            $('#product-table').trigger('table:changed');
         });
         $('.product').trigger('change');

        $('#product-table').on('change', '.product', function () {
            var $this = $(this),
                tr = $this.closest('tr'),
                product = tr.find('.product').val();
            if(!product) return;
            var productInfo = products[product],
                price = productInfo['price'].toFixed(2);
            tr.find('.product-price').text(productInfo['price'].toFixed(2));
            tr.find('.unit-price').val(price);
            $('#product-table').trigger('table:changed');
        });

        $('#product-table').on('change', '.quantity', function () {
            var $this = $(this),
                tr = $this.closest('tr'),
                product = tr.find('.product').val();

            if(!product) return;
            var productInfo = products[product],
                unit_price = parseFloat(tr.find('.unit-price').val()),
                amount = parseFloat(unit_price * parseFloat($this.val()));
            tr.find('.amount').text(amount.toFixed(2));
            $('#product-table').trigger('table:changed');
        });
        $('.quantity').trigger('change');

        $('.add-line').click(function() {
            var table = $(this).closest('table.dynamic'),
                clone = table.find('tbody tr:first').clone();
            clone.find('select,input:not([type=hidden])')
                .attr('name', function () {
                    return $(this).data('name').replace('idx', table.find('tbody tr').length)
                })
                .val('');

            clone.find('.clear').html('')
            clone.find('[type=hidden]').remove('')
            clone.appendTo(table.find('tbody'))
        })

        $('table.dynamic').on('click', '.remove-line', function () {
            var table = $(this).closest('table.dynamic'),
                tr = table.find('tbody tr')
                id = table.attr('id');
            if(tr.length === 1){
                tr.find('select,input').val('')
                tr .find('.clear').html('')
                tr.find('[type=hidden]').remove('');
            }else{
                $(this).closest('tr').remove();
            }
            $('#'+id).trigger('table:changed');
        })

        $('#customer').change(function () {
            var $this = $(this),
                val = $this.val();

            if(!val) return;

            $.getJSON($this.data('get-pets-url').replace('__ID__', val))
                .done(function (res) {
                    $('.pets').html(function () {
                        var options = '<option></option>';
                        $.each(res.data, function (i, v) {
                            options += '<option value="'+v['id']+'">'+v['name']+' ('+v['breed']['description']+')</option>';
                        })
                        return options;
                    })
                })
        })

        $('#product-table').on('table:changed', function () {
            $(this).find('#product-total').text(getProducTotal().toFixed(2))
        }).trigger('table:changed');

         $('#service-table').on('table:changed', function () {
            $(this).find('#service-total').text(getServiceTotal().toFixed(2))
        }).trigger('table:changed');

         $('[name="parent[appointment_status]"]').change(function(e) {
             if($(this).val() === 'DENIED'){
                $('[name="parent[status_remarks]"]').closest('.form-group').removeClass('d-none');
             }else{
                $('[name="parent[status_remarks]"]').closest('.form-group').addClass('d-none');
             }
         }).trigger('change');

         if($('[name="parent[appointment_status]"]').val() === 'DENIED' || $('[name="parent[appointment_status]"]').val() === 'CANCELLED'){
            $('.form-control').attr('readonly', 'readonly').addClass('form-control-plaintext');
            $('.custom-select').addClass('d-none').after(function () {
                console.log($(this).find('option:selected').text())
                return $('<input />', {
                    type:'text',
                    'class': 'form-control form-control-plaintext',
                    readonly: 'readonly',
                    value: $(this).find('option:selected').text()
                })
            })
            $('[type=submit]').remove();
         }
    });
</script>
@endpush
