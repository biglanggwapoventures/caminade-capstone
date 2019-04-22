<div class="container">
    <div class="gateway--info">
        <div class="gateway--paypal">
            @if(session()->has('message'))
                <p class="message">
                    {{ session('message') }}
                </p>
            @endif
            <form method="POST" action="{{ route('checkout.payment.paypal', ['order' => encrypt(mt_rand(1, 140))]) }}">
                {{ csrf_field() }}
                <button class="btn btn-pay">
                    <i class="fa fa-paypal" aria-hidden="true"></i> Pay with PayPal
                </button>
            </form>
        </div>
    </div>
</div>