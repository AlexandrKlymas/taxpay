$(document).ready(function() {
    /* menu */
    $('.nav-trigger').on('click', function(){
        $('#main-header').toggleClass('active');
        $('.nav-trigger').toggleClass('active');
        $('body').toggleClass('menu-active');
        return false;
    });

    $('.search-trigger').on('click', function(){
        $('#main-header .search-wrap').toggleClass('active');
        $(this).toggleClass('search-active');
        return false;
    });

    /**/
    $('.input-phone').inputmask({"mask": "+38 (999) 999-99-99"});

    /**/
    $('.btn-edit').on('click', function(){
        $(this).parent().parent('.data-row').addClass('edited');
        return false;
    });

});

// function initDataPickers(){
//     const dateRangePicker = document.querySelectorAll('[data-range-duble]');
//     const dateRangePickerSingle = document.querySelectorAll('[data-range-single]');
//
//     const dateRangeDoubleConfig = {
//         linkedCalendars: true,
//         timePicker: false,
//         ranges: {
//             'Тиждень': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
//             'Місяць': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
//             'Рік': [moment().startOf('year').startOf('day'), moment().endOf('year').endOf('day')],
//         },
//         locale: {
//             direction: 'ltr',
//             format: "DD.MM.YYYY",
//             monthNames: ['Січень','Лютий','Березень','Квітень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'],
//             daysOfWeek: ['Нд','Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
//             customRangeLabel: "Вибрати",
//             applyLabel: "Продовжити",
//             cancelLabel: "Відмінити",
//             separator: ' - ',
//             firstDay: ''
//         }
//     }
//     const dateRangeSingleConfig =  {
//         singleDatePicker: true,
//         //autoApply: true,
//         locale: {
//             direction: 'ltr',
//             format: "DD.MM.YYYY",
//             applyLabel: "Продовжити",
//             monthNames: ['Січень','Лютий','Березень','Квітень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'],
//             daysOfWeek: ['Нд','Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
//             firstDay: ''
//         }
//     };
//
//     dateRangePicker.forEach(item => {
//         new DateRangePicker(item, dateRangeDoubleConfig);
//     });
//
//     dateRangePickerSingle.forEach(item => {
//         new DateRangePicker(item, dateRangeSingleConfig);
//     });
// }
//
// window.addEventListener("load", function (event) {
//     // initDataPickers();
// });

$(function() {
    // $('.datefilter').daterangepicker({
    //     autoUpdateInput: false,
    //     linkedCalendars: true,
    //     timePicker: false,
    //     startDate: false,
    //     endDate: false,
    //     time: {
    //         enabled: false
    //     },
    //     ranges: {
    //         'Тиждень': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
    //         'Місяць': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
    //         'Рік': [moment().startOf('year').startOf('day'), moment().endOf('year').endOf('day')],
    //     },
    //     locale: {
    //         format: "DD.MM.YYYY",
    //         monthNames: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
    //         daysOfWeek: ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    //         customRangeLabel: "Вибрати",
    //         applyLabel: "Продовжити",
    //         cancelLabel: "Відмінити",
    //         separator: ' - ',
    //     }
    // });
    //
    // $('.datefilter').on('apply.daterangepicker', function(ev, picker) {
    //     $(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
    // });
    //
    // $('.datefilter').on('cancel.daterangepicker', function(ev, picker) {
    //     $(this).val('');
    // });

    $('[data-range-single]').each(function(i, obj) {
        $(this).daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: "DD.MM.YYYY",
                applyLabel: "Підтвердити",
                monthNames: ['Січень','Лютий','Березень','Квітень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'],
                daysOfWeek: ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            }
        });
        $(this).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD.MM.YYYY'));
        });

        $(this).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });


});

