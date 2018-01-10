@extends('admin.layouts.main')
@push('css')
@endpush
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
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
                             {!! Form::bsDate('parent[appointment_date]', 'Date', $resourceData->appointment_date) !!}
                        </div>
                        <div class="col">
                            {!! Form::bsTime('parent[appointment_time]', 'Time', $resourceData->appointment_time) !!}
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    {!! Form::bsSelect('parent[doctor_id]', 'Assgined Doctor', $doctorList, $resourceData->doctor_id, ['class' => 'custom-select w-100']) !!}
                </div>
            </div>
            {!! Form::bsTextarea('parent[remarks]', 'Remarks', null, ['rows' => 3]) !!}
            <h4 class="mt-5">Services Rendered</h4>
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
                            <td>{!! Form::bsSelect('child[0][service_id]', null, $serviceList,  null, ['class' => 'custom-select w-100 service', 'data-name' => 'child[idx][service_id]']) !!}</td>
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
            <h4 class="mt-5">Products Used</h4>
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
                            <td>{!! Form::bsText('products[0][quantity]', null, null, ['data-name' => 'products[idx][quantity]', 'class' => 'form-control quantity']) !!}</td>
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
                                <td>{!! Form::bsText("products[{$loop->index}][quantity]", null, $row->quantity, ['data-name' => 'products[idx][quantity]', 'class' => 'form-control quantity']) !!}</td>
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
            </div>
            <h4 class="mt-5">Post Appointment Findings</h4>
            {!! Form::bsTextarea('parent[findings]', null, $resourceData->findings, ['rows' => 4]) !!}
            <hr>
            <div class="row">
                <div class="col-4">
                    {!! Form::bsSelect('parent[appointment_status]', 'Set appointment status', ['PENDING' => 'Pending', 'APPROVED' => 'Approved', 'DENIED' => 'Rejected'],$resourceData->appointment_status,['class' => 'custom-select w-100']) !!}
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
                var serviceInfo = services[service];
                total += (parseFloat(serviceInfo['price']) || 0)
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
                total +=  (parseFloat(productInfo['price']) * quantity || 0)
            })
            return total;
        }

        $('#service-table').on('change', '.service', function () {
            var $this = $(this),
                val = $(this).val();
            if(!val) return;
            var info = services[val],
                tr = $this.closest('tr');
            tr.find('.service-duration').text(info['duration']+' minutes');
            tr.find('.service-price').text(info['price']+' php');
            $('#service-table').trigger('table:changed');
        });
        $('.service').trigger('change');

         $('#product-table').on('change', '.product', function () {
            var $this = $(this),
                tr = $this.closest('tr'),
                product = tr.find('.product').val();
            if(!product) return;
            var productInfo = products[product];
            tr.find('.product-price').text(productInfo['price'].toFixed(2));
            $('#product-table').trigger('table:changed');
         });
         $('.product').trigger('change');


        $('#product-table').on('change', '.quantity', function () {
            var $this = $(this),
                tr = $this.closest('tr'),
                product = tr.find('.product').val();

            if(!product) return;
            var productInfo = products[product],
                amount = parseFloat(productInfo['price']) * parseFloat($this.val());
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
                    .end()
                    .find('.clear').html('')
                    end()
                    .find('[type=hidden]').remove('');
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
    });
</script>
@endpush
