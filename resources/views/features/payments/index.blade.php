@extends('layouts.app')

@section('content')
    @if (session('success-message'))
        <div class="alert alert-success">
            {{session('success-message')}}
        </div>
    @endif
    @if (count($errors) > 0)
        {{-- @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                {{$error}}
            </div>
        @endforeach --}}
        <script>
            Swal.fire(
                'Oops!',
                'Qualcosa è andato storto!',
                'error'
            )
        </script>

    @endif
    <div class="container pt-5">
        <div class="d-flex justify-content-center">
            <form class="text-center" method="post" id="payment-form" action="{{route('payments.checkout', [$sponsorship, $apartment])}}">
                @csrf
                <section>
                    <label for="amount">
                        <span class="input-label" readonly>Prezzo</span>
                        <div class="input-wrapper amount-wrapper">
                            <input readonly id="amount" name="amount" type="tel" min="1" placeholder="Amount" value="{{$amount}}">
                        </div>
                    </label>

                    <div class="bt-drop-in-wrapper text-start">
                        <div id="bt-dropin"></div>
                    </div>
                </section>

                <input id="nonce" name="payment_method_nonce" type="hidden" />
                <button class="btn btn-info mt-2" type="submit"><span>Procedi e Paga</span></button>
            </form>
        </div>
    </div>

@endsection
@section('footer-scripts')
    <script src="https://js.braintreegateway.com/web/dropin/1.33.2/js/dropin.min.js"></script>
    <script>
        var form = document.querySelector('#payment-form');
        var client_token = "{{ $token }}";

        braintree.dropin.create({
            authorization: client_token,
            selector: '#bt-dropin',
            locale: 'it_IT',
            // paypal: {
            //     flow: 'vault'
            // }
        }, function (createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                instance.requestPaymentMethod(function (err, payload) {
                if (err) {
                    console.log('Request Payment Method Error', err);
                    return;
                }

                // Add the nonce to the form and submit
                document.querySelector('#nonce').value = payload.nonce;
                form.submit();
                });
            });
        });
    </script>
@endsection
