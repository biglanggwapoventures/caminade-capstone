@extends('admin.layouts.main')
@push('css')
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker-standalone.css') }}">
    <style type="text/css">


    .table .form-group{
      margin-bottom:0px;
    }

    .table, .card{
      z-index: 0!important;
    }

    .bootstrap-datetimepicker-widget{
        z-index: 2000!important;
        /*width:100%!important;*/
    }
    </style>

@endpush

@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Boarding</h2>
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
                <div class="col-sm-3">
                    {!! Form::bsText('appointment_id', 'Appointment Ref #', $resourceData->appointment_id ?: request()->input('appointment-id'), ['class' => 'form-control form-control-plaintext', 'readonly' => 'readonly']) !!}
                </div>
                <div class="col-sm-5">
                    <div class="row">
                        <div class="col">
                             {!! Form::bsText('timestamp_in', 'Date &amp; time in', null, ['class' => 'form-control datetimepicker']) !!}
                        </div>
                        <div class="col">
                            {!! Form::bsText('timestamp_out', 'Date &amp; time out', null, ['class' => 'form-control datetimepicker']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    {!! Form::bsText('price_per_day', 'Price per day', null, ['class' => 'form-control text-right']) !!}
                </div>
            </div>
            <h4 class="mt-5">Pet Journal</h4>
            <div class="card">
                <table class="table dynamic mb-0">
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
                        @forelse($resourceData->petJournals as $row)
                        <tr>
                            <td>
                                {!! Form::bsSelect("pet_logs[{$loop->index}][pet_id]", null, $customerPets,  $row->pet_id, ['class' => 'custom-select w-100 pets', 'data-name' => 'pet_logs[idx][pet_id]']) !!}
                            </td>
                            <td>
                                {!! Form::bsDate("pet_logs[{$loop->index}][log_date]", null, $row->log_date, ['data-name' => 'pet_logs[idx][log_date]']) !!}
                            </td>
                            <td>
                                {!! Form::bsTime("pet_logs[{$loop->index}][log_time]", null, $row->log_time, ['data-name' => 'pet_logs[idx][log_time]']) !!}
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
                                    {!! Form::bsDate('pet_logs[0][log_date]', null, null, ['data-name' => 'pet_logs[idx][log_date]']) !!}
                                </td>
                                <td>
                                    {!! Form::bsTime('pet_logs[0][log_time]', null, null, ['data-name' => 'pet_logs[idx][log_time]']) !!}
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
            </div>
            <h4 class="mt-5">Products Used (Optional)</h4>
            <div class="card">
                <table class="table dynamic mb-0" id="product-table"  data-product-details="{{ $productInfo->toJson() }}">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white">Date</th>
                            <th class="bg-secondary text-white">Time</th>
                            <th class="bg-secondary text-white">Product</th>
                            <th class="bg-secondary text-white">Quantity</th>
                            <th class="bg-secondary text-white">Unit Price</th>
                            <th class="bg-secondary text-white">Discount</th>
                            <th class="bg-secondary text-white">Amount</th>
                            <th class="bg-secondary text-white"></th>
                        </tr>
                    </thead>
                    <tbody>
                         @forelse($resourceData->productsUsed as $row)
                         <tr>
                            <td>{!! Form::bsDate("products[{$loop->index}][date_used]", null, $row->date_used, ['data-name' => 'products[idx][date_used]']) !!}</td>
                            <td>{!! Form::bsTime("products[{$loop->index}][time_used]", null, $row->time_used, ['data-name' => 'products[idx][time_used]']) !!}</td>
                            <td>
                                {!! Form::bsSelect("products[{$loop->index}][product_id]", null, $productList, $row->product_id, ['class' => 'product custom-select  w-100', 'data-name' => 'products[idx][product_id]']) !!}
                                {!! Form::hidden("products[{$loop->index}][id]", $row->id) !!}
                            </td>
                            <td>{!! Form::bsText("products[{$loop->index}][quantity]", null, $row->quantity, ['data-name' => 'products[idx][quantity]', 'class' => 'form-control quantity text-right']) !!}</td>
                            <td>{!! Form::bsText("products[{$loop->index}][unit_price]", null, $row->unit_price, ['data-name' => 'products[idx][unit_price]', 'class' => 'form-control text-right unit-price']) !!}</td>
                            <td>{!! Form::bsText("products[{$loop->index}][discount]", null, $row->discount, ['data-name' => 'products[idx][discount]', 'class' => 'form-control text-right discount']) !!}</td>
                            <td class="amount clear"></td>
                            <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                        </tr>
                        @empty
                            <tr>
                                <td>{!! Form::bsDate('products[0][date_used]', null, null, ['data-name' => 'products[idx][date_used]']) !!}</td>
                                <td>{!! Form::bsTime('products[0][time_used]', null, null,['data-name' => 'products[idx][time_used]']) !!}</td>
                                <td>{!! Form::bsSelect('products[0][product_id]', null, $productList, null, ['class' => 'product custom-select  w-100', 'data-name' => 'products[idx][product_id]']) !!}</td>
                                <td>{!! Form::bsText('products[0][quantity]', null, null, ['data-name' => 'products[idx][quantity]', 'class' => 'form-control quantity text-right']) !!}</td>
                                <td>{!! Form::bsText('products[0][unit_price]', null, null, ['data-name' => 'products[idx][unit_price]', 'class' => 'form-control unit-price text-right']) !!}</td>
                                <td>{!! Form::bsText('products[0][discount]', null, null, ['data-name' => 'products[idx][discount]', 'class' => 'form-control discount text-right']) !!}</td>
                                <td class="amount clear"></td>
                                <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                            </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                         <td ><button type="button" class="btn btn-secondary  add-line"><i class="fas fa-plus"></i> New product</button></td>
                            <td colspan="5" class="text-right">Total</td>
                            <td id="product-total"> </td>
                            <td></td>
                    </tfoot>
                </table>
            </div>
            <hr>
            <button type="submit" class="btn btn-success">Save</button>
        {!! Form::close() !!}
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {


        $('.datetimepicker').each(function () {
            var $this = $(this);
            $this.val($this.val() ? moment($this.val()).format('MM/DD/YYYY hh:mm A') : null);
        })
        $('.datetimepicker').datetimepicker({
            format:'MM/DD/YYYY hh:mm A'
        });

        var products = $('#product-table').data('product-details'),
            calculateLine = function (el) {
                var tr = el.closest('tr'),
                    productId = tr.find('.product').val();

                if(!productId)return;

                var productInfo = products[productId],
                    unitPrice = parseFloat(el.hasClass('product') ? productInfo['price'] : (tr.find('.unit-price').val() || 0)),
                    discount = parseFloat(tr.find('.discount').val() || 0),
                    quantity = parseFloat(tr.find('.quantity').val() || 0);

                tr.find('.unit-price').val(unitPrice.toFixed(2));
                tr.find('.amount').text(((unitPrice - discount) * quantity).toFixed(2));
                tr.closest('table').trigger('table:changed');
            };


        function getProducTotal() {
             var total = 0;
            $('#product-table tbody tr').each(function () {
                var tr = $(this),
                    productId = tr.find('.product').val();

                if(!productId)return;

                var productInfo = products[productId],
                    unitPrice = tr.find('.unit-price').val() || 0,
                    discount = parseFloat(tr.find('.discount').val() || 0),
                    quantity = parseFloat(tr.find('.quantity').val() || 0);

                total += ((unitPrice - discount) * quantity);
            })
            return total;
        }

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

         $('#product-table').on('change', '.product, .unit-price, .quantity, .discount', function () {
            console.log($(this).data('name'))
            calculateLine($(this));
         });
         $('.unit-price').trigger('change');


        $('#product-table').on('table:changed', function () {
            $(this).find('#product-total').text(getProducTotal().toFixed(2))
        }).trigger('table:changed');
    });
</script>
@endpush
