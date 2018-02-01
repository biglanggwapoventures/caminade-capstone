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
        <h2>Orders</h2>
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
                <div class="col-4">
                    {!! Form::bsSelect('order_type', 'Order Type', ['' => '', 'IN_HOUSE' => 'In House', 'WALK_IN'=> 'Walk In']) !!}
                </div>
                <div class="col-4 d-none fg">
                    {!! Form::bsSelect('parent[customer_id]', 'Customer', $customerList, $resourceData->customer_id, ['class' => 'custom-select w-100']) !!}
                </div>
                <div class="col-4 d-none fg">
                    {!! Form::bsText('parent[customer_name]', 'Customer Name', $resourceData->customer_name, ['class' => 'form-control']) !!}
                </div>
            </div>
            {!! Form::bsTextarea('parent[remarks]', 'Remarks', null, ['rows' => 3]) !!}
            <h4 class="mt-5">Products</h4>
            <div class="card">
                <table class="table dynamic mb-0" id="product-table"  data-product-details="{{ $productInfo->toJson() }}">
                    <thead>
                        <tr>
                            <th class="bg-secondary text-white">Product</th>
                            <th class="bg-secondary text-white">Quantity</th>
                            <th class="bg-secondary text-white">Price</th>
                            <th class="bg-secondary text-white">Discount</th>
                            <th class="bg-secondary text-white">Amount</th>
                            <th class="bg-secondary text-white"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resourceData->line AS $row)
                        <tr>
                            <td>
                                {!! Form::bsSelect("child[{$loop->index}][product_id]", null, $productList, $row->product_id, ['class' => 'product custom-select  w-100', 'data-name' => 'child[idx][product_id]']) !!}
                                {!! Form::hidden("child[{$loop->index}][id]", $row->id, ['class' => 'purge']) !!}
                            </td>
                            <td>
                                {!! Form::bsText("child[{$loop->index}][quantity]", null, $row->quantity, ['data-name' => 'child[idx][quantity]', 'class' => 'form-control quantity']) !!}
                                {!! Form::hidden('child[0][stock]', null, ['data-name' => 'child[idx][stock]', 'class' => 'stock ignore']) !!}
                            </td>
                            <td class="product-price clear"></td>
                            <td>
                                {!! Form::hidden("child[{$loop->index}][unit_price]", null, ['data-name' => 'child[idx][unit_price]', 'class' => 'form-control unit-price ignore']) !!}
                                {!! Form::bsText("child[{$loop->index}][discount]", null, $row->discount, ['data-name' => 'child[idx][discount]', 'class' => 'form-control discount']) !!}
                            </td>
                            <td class="amount clear"></td>
                            <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                        </tr>

                        @empty
                        <tr>
                            <td>{!! Form::bsSelect('child[0][product_id]', null, $productList, null, ['class' => 'product custom-select  w-100', 'data-name' => 'child[idx][product_id]']) !!}</td>
                            <td>
                                {!! Form::bsText('child[0][quantity]', null, null, ['data-name' => 'child[idx][quantity]', 'class' => 'form-control quantity']) !!}
                                {!! Form::hidden('child[0][stock]', null, ['data-name' => 'child[idx][stock]', 'class' => 'stock ignore']) !!}
                            </td>
                            <td class="product-price clear"></td>
                            <td>
                                {!! Form::hidden('child[0][unit_price]', null, ['data-name' => 'child[idx][unit_price]', 'class' => 'unit-price ignore']) !!}
                                {!! Form::bsText('child[0][discount]', null, null, ['data-name' => 'child[idx][discount]', 'class' => 'form-control discount']) !!}
                            </td>
                            <td class="amount clear"></td>
                            <td><button class="btn btn-danger remove-line" type="button"><i class="fas fa-times"></i></button></td>
                        </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                         <td ><button type="button" class="btn btn-secondary  add-line"><i class="fas fa-plus"></i> New product</button></td>
                            <td colspan="3" class="text-right">Total</td>
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
<script type="text/javascript">
    jQuery(document).ready(function($) {
        var products = $('#product-table').data('product-details');

        function getProducTotal() {
             var total = 0;
            $('#product-table tbody tr').each(function () {
                var $this = $(this),
                    product = $this.find('.product').val();
                if(!product) return;
                var productInfo = products[product],
                    quantity = parseFloat($this.find('.quantity').val() || 0),
                    discount = parseFloat($this.find('.discount').val() || 0);
                total +=  (parseFloat(productInfo['price'] - discount) * quantity || 0)
            })
            return total;
        }

         $('#product-table').on('change', '.product', function () {
            var $this = $(this),
                tr = $this.closest('tr'),
                product = tr.find('.product').val();

            if(!product) return;

            var productInfo = products[product],
                unitPrice = productInfo['price'].toFixed(2),
                stock = productInfo['stock_on_hand'].toFixed(2);

            tr.find('.product-price').text(unitPrice);
            tr.find('.unit-price').val(unitPrice);
            tr.find('.stock').val(stock);

            $('#product-table').trigger('table:changed');
            $('.quantity').trigger('change');
         });
         $('.product').trigger('change');


        $('#product-table').on('change', '.quantity,.discount', function () {
            var $this = $(this),
                tr = $this.closest('tr'),
                product = tr.find('.product').val();

            if(!product) return;
            var productInfo = products[product],
                unitPrice = parseFloat(productInfo['price'].toFixed(2)),
                discount = parseFloat(tr.find('.discount').val() || 0),
                quantity = parseFloat(tr.find('.quantity').val() || 0);

                amount = (unitPrice - discount) * quantity;
            tr.find('.amount').text(amount.toFixed(2));
            $('#product-table').trigger('table:changed');
        });
        $('.quantity').trigger('change');

        $('.add-line').click(function() {
            var table = $(this).closest('table.dynamic'),
                clone = table.find('tbody tr:first').clone();
            clone.find('select,input:not(.purge)')
                .attr('name', function () {
                    return $(this).data('name').replace('idx', table.find('tbody tr').length)
                })
                .val('');

            clone.find('.clear').html('')
            clone.find('input[type=hidden].purge').remove('')
            clone.appendTo(table.find('tbody'))
        })

        $('table.dynamic').on('click', '.remove-line', function () {
            var table = $(this).closest('table.dynamic'),
                tr = table.find('tbody tr')
                id = table.attr('id');
            if(tr.length === 1){
                tr.find('select,input').val('')
                tr .find('.clear').html('')
                tr.find('[type=hidden]:not(.ignore)').remove();
            }else{
                $(this).closest('tr').remove();
            }
            $('#'+id).trigger('table:changed');
        })

        $('#product-table').on('table:changed', function () {
            $(this).find('#product-total').text(getProducTotal().toFixed(2))
        }).trigger('table:changed');

        $('[name=order_type]').change(function () {
            var type = $(this).val();
            if(type === 'WALK_IN'){
                $('[name="parent[customer_name]"]').closest('.fg').removeClass('d-none');
                $('[name="parent[customer_id]"]').closest('.fg').addClass('d-none');
            }else if(type === 'IN_HOUSE'){
               $('[name="parent[customer_name]"').closest('.fg').addClass('d-none');
                $('[name="parent[customer_id]"]').closest('.fg').removeClass('d-none');
            }
        }).trigger('change');
    });
</script>
@endpush
