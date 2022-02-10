
$.formUtils.addValidator({
    name : 'minsumm',
    validatorFunction : function(value, $el, config, language, $form) {

        var min = parseFloat($el.valAttr('minsumm'));



        this.errorMessage = $el.valAttr('errormsgminsumm');
        return parseFloat(value) >= min;
    },
    errorMessage : '',
    errorMessageKey: '',
});

$.formUtils.addValidator({
    name : 'tochno',
    validatorFunction : function(value, $el, config, language, $form) {
        this.errorMessage = $el.valAttr('errormsg');
        var pattern = new RegExp($el.valAttr('pattern'), "i");

        console.log(pattern.test(value))

        return pattern.test(value);
    },
    errorMessage : '',
    errorMessageKey: '',
});


$.formUtils.addValidator({
    name : 'checkpattern',

    validatorFunction : function(value, $el, config, language, $form) {

        this.errorMessage = $el.valAttr('errormsg');

        var pattern = new RegExp($el.valAttr('pattern'), "i");

        console.log(pattern.test(value))

        return !pattern.test(value);
    },
    errorMessage : '',
    errorMessageKey: '',
});


$.validate({
    modules : 'logic',
    form : '[data-validation-form]',
    lang : 'ua'
});
