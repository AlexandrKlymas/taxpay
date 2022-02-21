let $serviceField = $('#service_field');

function addServicePrice(price) {

    if (!$serviceField.length) {
        return price;
    }

    let option = $serviceField.find('option:selected');
    let servicePrice = option.data('price') | 0;

    price += servicePrice;

    return price;
}

function addSumFieldPrice(price) {

    let $field = $('.js-sum-field');

    if ($field.length && $field.val() !== '') {

        let value = parseFloat($field.val());
        price += value;
    }

    return price;
}

function updateTotalPrice() {

    let price = addSumFieldPrice(0);

    price = addServicePrice(price);

    let totalCommissionConfig = commissionConfig.total;

    let commission = 0;

    if (price > 0) {

        if (totalCommissionConfig.fix !== "0.00") {
            commission = parseFloat(totalCommissionConfig.fix);
        } else if (totalCommissionConfig.percent !== "0.00") {
            commission = price * parseFloat(totalCommissionConfig.percent) / 100;

            if (totalCommissionConfig.min !== "0.00" && parseFloat(totalCommissionConfig.min) > commission) {
                commission = parseFloat(totalCommissionConfig.min);
            }
            if (totalCommissionConfig.max !== "0.00" && parseFloat(totalCommissionConfig.max) < commission) {
                commission = parseFloat(totalCommissionConfig.max);
            }
        }
    }

    let total = (price + commission).toFixed(2);

    $('.js-total-price').val(total).text(total);

    $('.js-total-input').val(total).text(total);
}

if (commissionConfig) {

    $(document)
        .ready(function () {
            updateTotalPrice()
        })
        .on('change', '#service_field', function () {
            updateTotalPrice()
        })
        .on('keyup', '.js-sum-field', function () {
            updateTotalPrice()
        })
    ;
}