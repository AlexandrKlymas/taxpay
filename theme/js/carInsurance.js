function getLicensePlateInfo(form){
    let carData = new FormData(form);
    let userDate = $('[data-user-info]').serializeArray();
    userDate.forEach(function (e){
        carData.append(e.name,e.value);
    })
    $('.wrap-spin').toggleClass('active');
    fetch('/get-license-plate-info', {
        method: 'post',
        body:carData
    })
        .then(res => res.json())
        .then(res => {
            if(res.error){
                alert(res.error);
            }

            if(res.car_info){
                document.querySelector('#car_info').innerHTML=res.car_info;
            }
            if(res.insurance_info){
                document.querySelector('#insurance_info').innerHTML=res.insurance_info;
            }
            if(res.info){
                document.querySelector('.payment-services-right .text-wrap')
                    .innerHTML = res.info;
            }
            if(document.querySelector('[name="programId"]').value.length>0){
                toggleForm('insurance_info');
                document.querySelector('[data-title-ua]').style.display = 'block';
            }
            setTimeOut(function(){
                $('.wrap-spin').toggleClass('active');
            },300);
            document.querySelector('.payment-services-left .text-wrap').innerHTML = '';
            initMasks();

        })
        .catch(res => {
            $('.wrap-spin').toggleClass('active');
            // alert('Вибачте, сервіс тимчасово недоступний. Спробуйте, будь ласка, пізніше.');
        });
}

function toggleForm(id){
    document.querySelectorAll('[data-formtoggler]').forEach(function (e){
        e.style.display = 'none';
    });
    document.querySelector('#'+id).style.display = 'block';
    switch (id){
        case 'car_info':
            document.querySelector('#programs').classList.remove('m-programs_b');
            document.querySelector('#programs').classList.add('m-programs_s');
            break;
        case 'insurance_info':
            document.querySelector('#programs').classList.remove('m-programs_s');
            document.querySelector('#programs').classList.add('m-programs_b');
            break;
    }
}

function getContract(form){
    $('.wrap-spin').toggleClass('active');
    let formData = new FormData(form);
    document.querySelectorAll('.insurance-select__radio').forEach(function (e){
        if(e.checked===true){
            formData.append('program_id',e.dataset.program_id);
        }
    });

    fetch('/get-contract', {
        method: 'post',
        body:formData
    })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'error'){
                alert(res.error);
            }else{
                if(res.car_info){
                    document.querySelector('#car_info').innerHTML=res.car_info;
                }
                if(res.insurance_info){
                    document.querySelector('#insurance_info').innerHTML=res.insurance_info;
                }
                if(res.payment){
                    document.querySelector('#payment').innerHTML=res.payment;
                    toggleForm('payment');
                    document.querySelector('#insurance-info-switcher').style.display = 'none';
                    document.querySelector('#car-info-switcher').style.display = 'none';
                    document.querySelector('[data-title-ua]').style.display = 'none';
                }
                if(res.payment_form){
                    document.querySelector('#payment_form').innerHTML=res.payment_form;
                }
                if(res.info){
                    document.querySelector('.payment-services-right .text-wrap')
                        .innerHTML = res.info;
                }
                initMasks();
            }
            $('.wrap-spin').toggleClass('active');

        })
        .catch(res => {
            $('.wrap-spin').toggleClass('active');
            // alert('Вибачте, сервіс тимчасово недоступний. Спробуйте, будь ласка, пізніше.');
        });
}

function selectCompany(input){
    document.querySelector('#sum').value=
        input.dataset.paysum;
    document.querySelector('[name="programId"]').value = input.dataset.program_id;
    document.querySelector('#insurance-info-switcher').style.display = 'block';
    formChanged();
}

function formChanged(){
    getLicensePlateInfo(document.querySelector('#osago_form'));
}

function setFranchise(franchise){
    document.querySelector('[name="franchise"]').value = franchise;
    document.querySelector('#franchise-price').innerHTML = franchise;
    formChanged();
}

function setSort(sort){
    document.querySelector('[name="sortBy"]').value = sort.dataset.sortBy;
    document.querySelector('[name="sortOrder"]').value = sort.dataset.sortOrder;
    if(sort.dataset.sortOrder === 'asc'){
        sort.dataset.sortOrder = 'desc';
    }else{
        sort.dataset.sortOrder = 'asc';
    }
    formChanged();
}

function pay(){
    let formData = new FormData();
    fetch('/pay-osago', {
        method: 'post',
        body:formData
    })
        .then(res => res.json())
        .then(res => {
            console.log(res);
        })
        .catch(res => {
            console.error(res)
        });
}

let InsuranceCalendarInputs;

function initMasks(){
    initDataPickers();
    $('#phone').mask("+38(999)999-99-99");
    $('#birthday').mask("99.99.9999");
    $('#driving_licence_date').mask("99.99.9999");
    $('#insurance_date').mask("99.99.9999");

    let uaOnly = /[^-а-щьюяА-ЩЬЮЯїЇєЄіІґҐ'’ʼ\s]{1,}/g;
    let uaDigital = /[^-.,а-щьюяА-ЩЬЮЯїЇєЄіІґҐ'’ʼ0-9\s]{1,}/g;
    setInputPattern('#fullname',uaOnly);
    setInputPattern('#address',uaDigital);
    setInputPattern('#driving_licence',uaDigital);
    setInputPattern('#issued_by',uaDigital);
}

function setInputPattern(id,regex){
    $(id).on('input', function() {
        let input = this;
        setTimeout(function (){
            input.value = input.value.replace(regex.exec(input.value), '');
        },100);

    });
}