<form data-action="/service-order-send-invoice-to-email" class="ajax-form" >
    <input type="hidden" name="order_hash" value="{{ $order_hash ?? '' }}">
    @if(isset($errors))
        <div class="alert alert-danger">
            @foreach($errors->all() as $message)
                {{ $message }} <br>
            @endforeach
        </div>
    @endif

    <div class="form-group">
        <label for="invoiceToEmailYourEmail">Email</label>
        <input id="invoiceToEmailYourEmail" name="email" value="{{ $email ?? '' }}" type="email" placeholder="Введите Email">
    </div>
    <div class="text-center">
        <input type="submit" class="btn" value="Надiслати">
    </div>
</form>
