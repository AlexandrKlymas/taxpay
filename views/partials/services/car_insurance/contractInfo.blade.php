<h3>Деталі договору</h3>

<table>
    <tr><td><h6>Компанія</h6></td><td>{{$user['program']['companyName']}}</td></tr>
    <tr><td><h6>Страхувальник</h6></td><td>
            {{$user['insurance_request']['insurantSurnameOrgName']}}
            {{$user['insurance_request']['insurantName']}}
            {{$user['insurance_request']['insurantPatronymic']}}
        </td></tr>
    <tr><td><h6>Телефон</h6></td><td>{{$user['insurance_request']['insurantPhone']}}</td></tr>
    <tr><td><h6>E-mail</h6></td><td>{{$user['insurance_request']['insurantEmail']}}</td></tr>
    <tr><td><h6>Документ</h6></td><td>
            {{$user['insurance_request']['insurantDocumentSeries']}}
            {{$user['insurance_request']['insurantDocumentNumber']}}
        </td></tr>
    <tr><td><h6>ТЗ</h6></td><td>{{$user['brand_model']}}</td></tr>
    <tr><td><h6>Тип</h6></td><td>{{$user['carTypeName']}}</td></tr>
    <tr><td><h6>VIN-код</h6></td><td>{{$user['vin']}}</td></tr>
    <tr><td><h6>Вартість</h6></td><td>{{$program['fullPrice']}} грн.</td></tr>
    <tr><td><h6>Франшиза</h6></td><td>{{$program['franchise']}} грн.</td></tr>
    <tr><td><h6>Термін дії</h6></td><td>{{$program['contractMonthes']}}</td></tr>
</table>