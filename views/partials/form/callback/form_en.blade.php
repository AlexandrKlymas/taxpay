<form data-action="ajax-form-callback" class="ajax-form">

    @if(isset($errors))
        <div class="alert alert-danger">
            @foreach($errors->all() as $message)
                {{ $message }} <br>
            @endforeach
        </div>
    @endif

    @if(isset($thanks))
        <div class="alert alert-success">
            {{ $thanks }}
        </div>
    @endif

    <div class="row form-row">
        <div class="col-4 form-group">
            <label for="callback_name">ім`я</label>
            <input id="callback_name" name="name" type="text" placeholder="Введите имя" value="{{ isset($form['name']) ? $form['name']:'' }}">
        </div>
        <div class="col-4 form-group">
            <label for="callback_phone">Телефон</label>
            <input id="callback_phone" class="input-phone" name="phone" type="tel" placeholder="+38 (___) ___-__-__" value="{{ isset($form['phone']) ? $form['phone'] :'' }}">
        </div>
        <div class="col-4 form-group">
            <label for="callback_email">email</label>
            <input id="callback_email" name="email" type="email" placeholder="Name@gmail.com" value="{{ isset($form['email'])?$form['email']:'' }}">
        </div>
    </div>

    <div class="form-group">
        <label for="callback_message">Повідомлення</label>
        <textarea id="callback_message" name="message" placeholder="Введіть повідомлення"> {{ isset($form['message'])?$form['message']:'' }} </textarea>
    </div>

    <div class="text-center">
        <input type="submit" class="btn" value="Надiслати">
    </div>
</form>