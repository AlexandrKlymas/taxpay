<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="media/style/{{$manager_theme}}/style.css"/>
    <script type="application/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body style="background-color:#EEEEEE">

@if(empty($_GET['registry_office_id']))
    <h1>РАЦСи</h1>
@else
    <h1>{{$registryOffices[$_GET['registry_office_id']]['name']}}</h1>
    <button type="button" onclick="window.location='{{$module_url}}&tab=main'">&lt; Повернутись</button>
    <br>
    <br>
@endif

<div class="sectionBody">
    <div id="modulePane" class="dynamic-tab-pane-control tab-pane">
        <div class="tab-row">
            @if($tab == 'registry_office')
                <h2 id="page2" class="tab @if($tab == "registry_office") selected @endif "><span
                            onClick="document.location.href='{{$module_url}}&tab=registry_office&registry_office_id={{$_GET['registry_office_id']}}'">Персонал</span>
                </h2>
            @else
                <h2 id="page1" class="tab @if($tab == "main") selected @endif "><span
                            onClick="document.location.href='{{$module_url}}&tab=main'">РАЦСи</span></h2>

                <h2 id="page4" class="tab @if($tab == "user_without_offices") selected @endif "><span
                            onClick="document.location.href='{{$module_url}}&tab=user_without_offices'">Користувачі без прив'язки</span>
                </h2>
            @endif
        </div>

        <div id="tab-page1" class="tab-page" @if($tab == "main") style="display:block;" @else style="display:none;" @endif>
            @if(isset($registryOffices))

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th scope="col" width="350px">Назва</th>
                        <th scope="col">QR</th>
                        <th scope="col" width="200px">Посилання</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($registryOffices as $registryOffice)
                        <tr>
                            <td style="width: 50px;">{{$loop->iteration}}</td>
                            <td>
                                <a href="{{$module_url}}&tab=registry_office&registry_office_id={{$registryOffice['id']}}"
                                   id="registry_office_{{$registryOffice['id']}}">{{$registryOffice['name']}}</a>
                                <i class="fa fa-edit" onclick="edit_registry_office({{$registryOffice['id']}})"></i>
                            </td>
                            <td>
                                <img width="200"
                                     src="{{(new chillerlan\QRCode\QRCode)
                            ->render(UrlProcessor::makeUrl(176,'','','full').'?registry_office='.$registryOffice['id'])}}"
                                     alt="{{$registryOffice['name']}}"/>
                            </td>
                            <td>
                                <a target="_blank" href="{{UrlProcessor::makeUrl(176,'','','full').'?registry_office='.$registryOffice['id']}}">
                                    {{UrlProcessor::makeUrl(176,'','','full').'?registry_office='.$registryOffice['id']}}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div id="tab-page2" class="tab-page" @if($tab == "registry_office") style="display:block;" @else style="display:none;" @endif>

            @if(isset($users))


                <h3>{{$registryOffice['name']}}</h3>


                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th scope="col">Ім'я</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">Telegram id</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Видалити</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($users as $user)
                        <tr id="url_{{$user['id']}}">
                            <td style="width: 50px;">{{$loop->iteration}}</td>
                            <td id="user_{{$user['id']}}">{{$user['name']}}</td>
                            <td>
                                {{$user['phone']}}
                            </td>
                            <td>
                                {{$user['telegram_id']}}
                            </td>
                            <td>
                                <select id="select_user_{{$user['id']}}" onchange="submitNewStatus({{$user['id']}})">
                                    <option value="0" @if($user['status'] == 0) SELECTED @endif>Не активний</option>
                                    <option value="1" @if($user['status'] == 1) SELECTED @endif>Активний</option>
                                </select>
                            </td>
                            <td>
                                <i class="fa fa-trash" onclick="delete_user({{$user['id']}})"></i>
                            </td>


                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <div class="row mb-5">
                    <div class="col-12">


                        <form method="post" class="form-inline">
                            <div class="form-group ">
                                <input type="text" class="form-control" name="name" placeholder="Ім'я менеджера">
                            </div>
                            <div class="form-group ">
                                <input type="text" class="form-control" name="phone" placeholder="Телефон менеджера">
                            </div>

                            <input type="hidden" name="registry_office_id" value="{{$_GET['registry_office_id']}}">

                            <button type="submit" class="btn btn-primary" value="Готово" name="confirm">Створити</button>
                        </form>


                    </div>
                </div>
            @endif

        </div>


        <div id="tab-page3" class="tab-page" @if($tab == "registry_office_commissions") style="display:block;" @else style="display:none;" @endif>
            @if(isset($userWithoutOffices))

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th scope="col">Ім'я</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">РАЦС</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($userWithoutOffices as $user)
                        <tr>
                            <td style="width: 50px;">{{$loop->iteration}}</td>
                            <td>
                                {{$user['name']}}
                            </td>

                            <td>
                                {{$user['phone']}}
                            </td>
                            <td>
                                <select id="select_office_{{$user['id']}}" onchange="submitNewData({{$user['id']}}, this.value)">
                                    <option value="">Виберіть РАЦС</option>
                                    @foreach($registryOffices as $office)
                                        <option value="{{$office['id']}}">{{$office['name']}}</option>
                                    @endforeach
                                </select>
                            </td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div id="tab-page4" class="tab-page"
             @if($tab == "user_without_offices") style="display:block;" @else style="display:none;" @endif>

            @if(isset($userWithoutOffices))

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th scope="col">Ім'я</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">РАЦС</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($userWithoutOffices as $user)
                        <tr>
                            <td style="width: 50px;">{{$loop->iteration}}</td>
                            <td>
                                {{$user['name']}}
                            </td>

                            <td>
                                {{$user['phone']}}
                            </td>
                            <td>
                                <select id="select_office_{{$user['id']}}" onchange="submitNewData({{$user['id']}},this.value);">
                                    <option value="">Виберіть РАЦС</option>
                                    @foreach($registryOffices as $office)
                                        <option value="{{$office['id']}}">{{$office['name']}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>

<script type="application/javascript">
    function submitNewData(id,value) {
        if (value !== '') {
            $.post('{!! $module_url  !!}&tab=user_without_offices', {userid: id, office: value})
                .done(function (data) {
                    location.reload();
                });
        }
    }
</script>
<script>
    function submitNewStatus(id) {
        let value = $('#select_user_' + id).val();
        if (value !== '') {
            $.post('{!! $module_url  !!}&tab=registry_office', {userid: id, status: value})
                .done(function (data) {
                    location.reload();
                });
        }
    }
</script>

<script type="application/javascript">
    function edit_registry_office(id) {

        let name = $('#registry_office_' + id).html()
        let person = prompt("Задайте нову назву РАЦСу", name);
        if (person !== null) {
            $.post('{!! $module_url  !!}&tab=registry_office', {id: id, new_name: person})
                .done(function (data) {
                    $('#registry_office_' + id).html(person)
                });
        }
    }
</script>
<script type="application/javascript">
    function delete_registry_office(id) {

        let name = $('#registry_office_' + id).html()
        if (confirm('Ви насправді бажаєте видалити ' + name + '?')) {

            $.post('{!! $module_url  !!}&tab=registry_office', {delete_office_id: id})
                .done(function (data) {
                    location.reload();
                });

        }
    }
</script>
<script type="application/javascript">
    function delete_user(id) {

        let name = $('#user_' + id).html()
        if (confirm("Ви насправді бажаєте видалити '" + name + "'?")) {

            $.post('{!! $module_url  !!}&tab=registry_office', {delete_user_id: id})
                .done(function (data) {
                    location.reload();
                });

        }
    }
</script>
</body>
</html>
