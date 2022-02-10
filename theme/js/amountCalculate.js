function addSumFieldPrice(price) {
    var $field = $('.js-sum-field');

    if ($field.length && $field.val() !== '') {

        var value = parseFloat($field.val());
        price += value;
    }
    return price;
}

function updateTotalPrice() {

    var price = addSumFieldPrice(0);

    var totalCommissionConfig = commissionConfig.total;


    var commission = 0;

    if(price>0){


    if (totalCommissionConfig.fix_summ != "0.00") {
        commission = parseFloat(totalCommissionConfig.fix_summ);
    } else if (totalCommissionConfig.percent != "0") {
        commission = price * parseFloat(totalCommissionConfig.percent) / 100;
        if (totalCommissionConfig.min_summ != "0.00" && parseFloat(totalCommissionConfig.min_summ) > commission) {
            commission = parseFloat(totalCommissionConfig.min_summ);
        }
        if (totalCommissionConfig.max_summ != "0.00" && parseFloat(totalCommissionConfig.max_summ) < commission) {
            commission = parseFloat(totalCommissionConfig.max_summ);
        }
    }
    }
    var total = (price + commission).toFixed(2);

    $('.js-total-price').val(total).text(total);
}

$(document)
    .ready(function () {
        updateTotalPrice()
    })
    .on('keyup', '.js-sum-field', function () {
        updateTotalPrice()
    })
;