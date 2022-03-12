<div class="box verification-box">
    <div class="row">
        <div class="col-6">
            <h4 class="box-title">Payer</h4>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Full name:</span>
                    <span class="val">{{ $requestData['full_name'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-6">
            <h4 class="box-title">Recipient</h4>

            <div class="data-row">
                <div class="data">
                    <span class="row-name">USREOU:</span>
                    <span class="val">37993783</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Account:</span>
                    <span class="val">UA08899998033320939700026001</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Bank of beneficiary:</span>
                    <span class="val">JSC CB PRIVATBANK</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Purpose of payment:</span>
                    <span class="val">Assistance to Ukraine</span>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 box-wrap">
        <div class="box">
            <h4 class="box-title">Payment</h4>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Payment amount:</span>
                    <span class="val">{{$requestData['sum']}} USD</span>
                </div>
            </div>
            <form class="form-payment js-service-form" id="service-form" data-validation-form>
                <input type="hidden" name="service" value="179">
                @foreach($requestData as $name=>$value)
                    @if(!empty($value))
                        <input type="hidden" name="{{$name}}" value="{{$value}}">
                    @endif
                @endforeach
            </form>
        </div>
    </div>
    <div class="col-6 box-wrap">
        @include('partials.payment_methods')
    </div>
</div>

<div class="form-footer">

    <div class="back-wrap">
                <a href="{{ redirect()->back()->getTargetUrl() }}" class="back">Back</a>
    </div>
    <input type="button" class="btn" value="Pay" id="temp-button" onclick="validSum()">
</div>

<script>
    function validSum(){
        let button = document.querySelector('#temp-button');
        let sum = parseFloat(document.querySelector('[name="sum"]').value);
        if(sum>0){
            let i = 0;
            document.querySelectorAll('[name="payment_method"]').forEach(function (el){
                if(el.checked){
                    i++;
                }
            });
            if(i>0){
                button.setAttribute('type','submit');
                button.setAttribute('id','create-order-and-pay');
                setTimeout(function (){
                    $('#create-order-and-pay').click();
                },100);
            }else{
                alert('Choose a payment method');
            }
        }else{
            alert('Fill in the payment amount');
        }
    }
</script>