@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <h3 class="text-center mt-3 mb-4"><i class="fas fa-paw fa-2x"></i> <br>Our Products</h3>
    <div class="row">
        <div class="col-sm-3">
            <div class="card">
                <div class="card-header">Search from our products</div>
                <div class="card-body p-3">
                    {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
                    {!! Form::bsText('name', 'Search by name', null, ['class' => 'form-control']) !!}
                    @foreach($categories as $id => $name)
                    <div class="checkbox" style="margin-left:20px">
                        <label>
                            {!! Form::checkbox('category[]', $id, null) !!} {{ $name }}
                        </label>
                    </div>
                    @endforeach
                    <hr>
                    <button type="submit" class="btn btn-info btn-block">Search</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            @foreach ($data->chunk(4) as $chunk)
                <div class="row">
                    @foreach ($chunk as $item)
                        <div class="col-3">
                            <div class="card border-0">
                                <div style="height: 150px;background-image: url('{{ $item->photo_src  }}');background-position: center center;background-repeat: no-repeat;background-size: cover "></div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $item->category->description }}</h6>
                                    <p class="card-text">{{ $item->description }}</p>
                                    <a class="card-link"><i class="fas fa-money-bill-alt"></i> {{ number_format($item->price, 2) }}</a>
                                    @if($item->high_stock)
                                    <a class="card-link text-success"><i class="fas fa-check"></i> In-stock</a>
                                    @else
                                    <a class="card-link text-danger"><i class="fas fa-times"></i> Low stock</a>
                                    @endif
                                    @if(Auth::user())
                                    <br>
                                    <a href="#" class="show-cart-modal"  data-product-id='{{ $item->id }}' data-product-price='{{ $item->price }}'
                                        modal = $('#cart-modal')><i class="fas fa-cart-plus"></i> Add to cart</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@push('modals')
<div class="modal fade" id="cart-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    {!! Form::open(['url' => route('add-to-cart'),  'method' => 'POST', 'id' => 'cart-form']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::bsText('product_quantity', 'Quantity') !!}
                {!! Form::hidden('product_id', null) !!}
                {!! Form::hidden('product_price', null) !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add-to-cart-button">Save changes</button>
            </div>
        </div>
    {!! Form::close() !!}
  </div>
</div>
@endpush

@push('scripts')
<script>
    jQuery(document).ready(function($) {
        $('.show-cart-modal').click(function(){
            
            var $this = $(this)
                product_id = $this.data('product-id')
                product_price = $this.data('product-price')
                cart_modal = $('#cart-modal')

            cart_modal.modal('show')
            cart_modal.find('form [name="product_id"]').val(product_id)
            cart_modal.find('form [name="product_price"]').val(product_price)
        }); 

        $('.add-to-cart-button').click(function(){
            var x = confirm("Add Item?");
            if(x) {
                var regex = /^[0-9]*$/;
            
                if(regex.test($('[name="product_quantity"]').val())) {
                    $('#cart-form').submit();
                } else {
                    alert('Please Input a number!');
                    return;
                }
            } else {
                return;
            }
        })

        $('#cart-form').on('submit', function(e){
            e.preventDefault()

            var product_id = $('[name="product_id"]').val()
                product_price = $('[name="product_price"]').val()
                product_quantity = $('[name="product_quantity"]').val()
                url = $(this).attr('action')

            $.post(url, {
                product_quantity, 
                product_id,
                product_price,
                _token: '{{ csrf_token() }}'
            }).done(function (res) {
                alert('Successfully Added!');
                $('#cart-modal').modal('hide');
            })
        })
    });
</script>
@endpush
