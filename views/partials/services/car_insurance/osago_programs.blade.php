<h5>Оберіть страхову компанію</h5>
<p>Щоб продовжити оформлення полісу треба обрати страхову компанію нижче.</p>

<div class="flex-block">
    <div>Франшиза  <a href="#franchiseModal" class="btn-callback" data-toggle="modal"><span id="franchise-price">
         @if(!empty($franchise)){{$franchise}}@else{{0}}@endif</span></a> грн.
    </div>
    <div >
        <a style="margin-right: 25px" class="sort-link @if($sort['sortBy']=='fullprice'){{'active'}}@endif @if($sort['sortOrder']=='desc'){{'rotate'}}@endif" href="javascript:void(0)" data-sort-by="fullprice"
           data-sort-order="@if($sort['sortOrder']=='asc'){{'desc'}}@else{{'asc'}}@endif" onclick="setSort(this)">
            За ціною
            <img src="/theme/images/osago/icon-range-o.svg">
        </a>
        <a class="sort-link @if($sort['sortBy']=='raiting'){{'active'}}@endif @if($sort['sortOrder']=='desc'){{'rotate'}}@endif" href="javascript:void(0)" data-sort-by="raiting"
           data-sort-order="@if($sort['sortOrder']=='desc'){{'asc'}}@else{{'desc'}}@endif" onclick="setSort(this)" >
            За рейтингом
            <img src="/theme/images/osago/icon-range-o.svg">
        </a>
    </div>
</div>
<hr>

<div id="programs" class="row m-programs m-programs_s scrollbar">
    @if(!empty($programs))
        @foreach($programs as $item)
            <div id="insurance-{{$item['id']??''}}" class="col col-6">
                <div class="card input-hidden">
                   <div class="card-header">
                       <img  src="https://polis.ua/service/resources/company/logo/{{$item['companyId']}}" alt="logo">
                   </div>
                    <div class="card-body">
                        <div><strong>{{$item['companyName']??''}}</strong></div>
                        <div >Рейтинг: {{$item['companyRate']}}/10</div>
                        <div class="costs"><span>{{$item['costs']??''}}</span> грн</div>
                        <input type="radio" name="insurance-company" id="insurance-company-{{$loop->iteration}}"
                               @if(!empty($program) && $item['id']==$program['id']){{'checked="checked"'}}@endif
                               data-company_id="{{$item['companyId']??''}}" data-program_id="{{$item['id']??''}}"
                               data-polis_type="{{$item['polisType']??''}}" data-paysum="{{$item['costs']??''}}" onchange="selectCompany(this)">
                        {{$item['risk']??''}}
                        <label for="insurance-company-{{$loop->iteration}}" class="btn choice-company">Обрати</label>
                        <button type="button" class="btn qs-disabled choice-company" disabled>Обрано</button>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>Стрвхові компанії не знайдено.</p>
    @endif
</div>
