@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <h3 class="text-center mt-3 mb-4"><i class="fas fa-paw fa-2x"></i> <br>My Cart</h3>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0 table-responsive">
                    <table class="table mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Product Name</th>
                                <th class="text-right">Unit Price</th>
                                <th style="width:20%" class="text-center">Quantity</th>
                                <th class="text-right">Amount</th>
                                <th style="width:30%" class="text-right"></th>
                            </tr>
                            <tbody>
                                @forelse($products as $item)
                                    <tr>
                                        <td>{{ $item['product_name'] }}</td>
                                        <td class="text-right selling-price" data-selling-price="{{ $item['product_price'] }}">
                                            {{ number_format($item['product_price'], 2) }}
                                        </td>
                                        <td class="text-right">
                                            <form action="{{ route('edit-cart') }}" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">                                            
                                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                <input type="hidden" name="product_price" value="{{ $item['product_price'] }}">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-primary btn-adjust" data-adjust="-"
                                                                type="button" id="button-addon2"><i
                                                                    class="fas fa-minus"></i></button>
                                                    </div>
                                                    <input type="number" class="form-control quantity text-center" name="quantity"
                                                            value="{{ $item['quantity'] ? $item['quantity'] : '0' }}" min="1">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success btn-adjust" data-adjust="+"
                                                                type="button" id="button-addon2"><i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-right amount">{{ number_format($item['quantity'] * $item['product_price'], 2) }}</td>
                                        <td>
                                            <button class="btn btn-success update-cart" type="button"><i class="fas fa-check"></i></button>
                                            <button class="btn btn-danger remove-product" type="button"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Items</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($products->count())
                                <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right border-0  font-weight-bold">Remarks:</td>
                                    <td class="border-0">
                                        <div class="form-group m-0">
                                            <textarea id="remarks" data-name="remarks"
                                                      class="form-control  needs-validation"></textarea>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" class="text-right  border-0  font-weight-bold">Total Amount:</td>
                                    <td class="text-right font-weight-bold border-0" id="total-amount"
                                        style="font-size:1.5rem"></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right border-0"></td>
                                    <td class="border-0">
                                        <button type="button"
                                                data-checkout-settings="{!! htmlspecialchars(json_encode(['url' => url('checkout-cart'), 'method' => 'post', 'token' => csrf_token()])) !!}"
                                                id="checkout-btn" class="btn btn-primary btn-block">
                                            Checkout
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
  <script type="text/javascript">
    $(document).ready(function () {

      getTotalAmount();

      $('.btn-adjust').click(function () {
        var $this = $(this),
          tr = $this.closest('tr');
        tr.find('.quantity').val(function () {
          var value = Number($(this).val()),
            newValue = $this.data('adjust') === '+'
              ? value + 1
              : value - 1

          return newValue >= 1 ? newValue : 1;
        }).trigger('change');
      });

      $('.quantity').on('keyup', function() {
        calculateLineAmount($(this).closest('tr'));
        getTotalAmount();
      });

      $('.quantity').change(function () {
        calculateLineAmount($(this).closest('tr'));
        getTotalAmount();
      });

      $('.update-cart').click(function () {
        var x = confirm('Update Item?');
        if(x) {
          updateCart($(this), "update");
        } else {
          return;
        }
      });

      $('.remove-product').click(function () {
        var x = confirm('Are you sure you want to delete this?');
        if(x) {
          updateCart($(this), "delete");
        } else {
          return;
        }
      });

      $('#checkout-btn').click(function () {
        var x = confirm('Are you sure you want to checkout?');
        
        // if confirm alert
        if(x) {
          var $this = $(this),
          content = $this.html(),
          checkoutSettings = $this.data('checkout-settings'),
          remarks = $('#remarks').val(),
          _token = checkoutSettings.token;

          $this.addClass('disabled')
            .html('<i class="fa fa-spin fa-spinner"></i>')

          $('.needs-validation').removeClass('is-invalid')
            .next('.invalid-feedback').remove();

          $.ajax({
            url: checkoutSettings.url,
            method: checkoutSettings.method,
            data: {remarks: remarks, _token: _token},
            success: function (response) {
              alert("Checkout Successful");
              window.location.href = '/';
            },
            error: function (xhr) {
              if (xhr.status === 422) {
                alert('Error, Please check!');
              }
            },
            complete: function () {
              $this.removeClass('disabled').html(content)
            }
          });

        } else {
          return;
        }
        
      });
    });

    function updateCart(btn, action) {
        var form = btn.closest('tr').find('form'),
          formData = new FormData(form[0]),
          content = btn.html();
        
        formData.append('action', action);

        btn.addClass('disabled')
          .html('<i class="fa fa-spin fa-spinner"></i>')

        $.ajax({
          method: form.attr('method'),
          url: form.attr('action'),
          data: formData,
          contentType: false,
          processData: false,
          success: function (res) {
            alert('Cart Successfully updated!');
            window.location.href = '/my-cart';
          },
          error: function () {
            window.alert('An internal server error has occured. Please refresh the page and try again!')
          },
          complete: function () {
            btn.removeClass('disabled').html(content)
          }
        })
    }

    function calculateLineAmount(row) {
      var quantity = Number(row.find('.quantity').val()),
        amount = Number(row.find('.selling-price').data('selling-price'));

      row.find('.amount').text((quantity * amount).toLocaleString(undefined, {
        minimumFractionDigits: 2
      }));
    }


    function getTotalAmount() {
      var total = 0;

      $('.table tbody tr').each(function () {
        var row = $(this),
        
          quantity = Number(row.find('.quantity').val()),
          sellingPrice = Number(row.find('.selling-price').data('selling-price'));

        total += (quantity * sellingPrice);
      })

      $("#total-amount").text(total.toLocaleString(undefined, {minimumFractionDigits: 2}));
    }
  </script>
@endpush