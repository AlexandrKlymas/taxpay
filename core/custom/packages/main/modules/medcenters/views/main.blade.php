<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="media/style/{{$manager_theme}}/style.css"/>
    <script type="application/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/theme/plugins/inputmask/jquery.inputmask.min.js"></script>
</head>

<body style="background-color:#EEEEEE">

<h1>Медицинские учреждения</h1>
{{--<div id="actions">--}}
{{--    <ul class="actionButtons">--}}
{{--        <li id="Button2"><a onclick="document.location.href='index.php?a=28';" href="#"><img--}}
{{--                        src="media/style/MODxCarbon/images/icons/stop.png">Change password</a></li>--}}

{{--        <li id="Button1"><a onclick="document.location.href='index.php?a=8';" href="#"><img--}}
{{--                        src="media/style/MODxCarbon/images/icons/stop.png">Log out</a></li>--}}
{{--    </ul>--}}
{{--</div>--}}
<div class="sectionBody">


    <div id="modulePane" class="dynamic-tab-pane-control tab-pane">
        <div class="tab-row">
            <h2 id="page1" class="tab @if($tab == "main") selected @endif "><span
                        onClick="document.location.href='{{$module_url}}&tab=main'">Мед центры</span></h2>
            @if($tab == 'medical_center')
                <h2 id="page2" class="tab @if($tab == "medical_center") selected @endif "><span
                            onClick="document.location.href='{{$module_url}}&tab=medical_center&medical_center_id={{$_GET['medical_center_id']}}'">Персонал</span>
                </h2>
            @endif

            <h2 id="page3" class="tab @if($tab == "user_without_centers") selected @endif "><span
                        onClick="document.location.href='{{$module_url}}&tab=user_without_centers'">Пользователи без привязки</span>
            </h2>

            @if($tab == 'check_homeworks')
                <h2 id="page2" class="tab @if($tab == "homeworks") selected @endif "><span
                            onClick="document.location.href='{{$module_url}}&tab=homeworks&module={{$siteHomeWorkDesc['id']}}'">Домашні завдання</span>
                </h2>
                <h2 id="page3" class="tab @if($tab == "check_homeworks") selected @endif "><span
                            onClick="document.location.href='{{$module_url}}&tab=check_homeworks&id_work={{$_GET['id_work']}}'">Завдання</span>
                </h2>
            @endif

        </div>

        <div id="tab-page1" class="tab-page" @if($tab == "main") style="display:block;"
             @else style="display:none;" @endif>
            @if(isset($medCenters))

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th scope="col" width="350px">Название</th>
                        <th scope="col">QR</th>
                        <th scope="col" width="20px">Удалить</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($medCenters as $medCenter)
                        <tr>
                            <td style="width: 50px;">{{$loop->iteration}}</td>
                            <td>
                                <a href="{{$module_url}}&tab=medical_center&medical_center_id={{$medCenter['id']}}"
                                   id="med_center_{{$medCenter['id']}}">{{$medCenter['name']}}</a>
                                <i class="fa fa-edit" onclick="edit_med_center({{$medCenter['id']}})"></i>
                            </td>
                            <td>
                                <img width="200px;"
                                     src="{{(new chillerlan\QRCode\QRCode)->render(UrlProcessor::makeUrl(170,'','','full').'?medical_center='.$medCenter['id'])}}"
                                     alt="{{$medCenter['name']}}"/>
                            </td>
                            <td>
                                <i class="fa fa-trash" onclick="delete_med_center({{$medCenter['id']}})"></i>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="row mb-5">
                    <div class="col-12">


                        <form method="post" class="form-inline">
                            <div class="form-group ">
                                <input type="text" class="form-control" name="name" placeholder="Название медцентра">
                            </div>
                            <button type="submit" class="btn btn-primary" value="Готово" name="confirm">Создать</button>
                        </form>


                    </div>
                </div>
            @endif
        </div>

        <div id="tab-page2" class="tab-page" @if($tab == "medical_center") style="display:block;"
             @else style="display:none;" @endif>

            @if(isset($users))


                <h3>{{$medicalCenter['name']}}</h3>


                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">Telegram id</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Удалить</th>

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
                                    <option value="0" @if($user['status'] == 0) SELECTED @endif>Не активный</option>
                                    <option value="1" @if($user['status'] == 1) SELECTED @endif>Активный</option>
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
                                <input type="text" class="form-control" name="name" placeholder="Имя менеджера">
                            </div>
                            <div class="form-group ">
                                <input type="text" class="form-control" name="phone" placeholder="Телефон менеджера"
                                       data-inputmask='"mask": "+99 (999) 99-99-999"' data-mask>
                            </div>

                            <input type="hidden" name="medical_center_id" value="{{$_GET['medical_center_id']}}">

                            <button type="submit" class="btn btn-primary" value="Готово" name="confirm">Создать</button>
                        </form>


                    </div>
                </div>
            @endif

        </div>

        <div id="tab-page3" class="tab-page" @if($tab == "user_without_centers") style="display:block;"
             @else style="display:none;" @endif>
            @if(isset($usesWithoutCenters))

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">Медцентр</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($usesWithoutCenters as $user)
                        <tr>
                            <td style="width: 50px;">{{$loop->iteration}}</td>
                            <td>
                                {{$user['name']}}
                            </td>

                            <td>
                                {{$user['phone']}}
                            </td>
                            <td>
                                <select id="select_center_{{$user['id']}}" onchange="submitNewData({{$user['id']}})">
                                    <option value="">Выберите медцентр</option>
                                    @foreach($medCenters as $center)
                                        <option value="{{$center['id']}}">{{$center['name']}}</option>
                                    @endforeach
                                </select>
                            </td>


                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="row mb-5">
                    <div class="col-12">


                        <form method="post" class="form-inline">
                            <div class="form-group ">
                                <input type="text" class="form-control" name="name" placeholder="Название медцентра">
                            </div>
                            <button type="submit" class="btn btn-primary" value="Готово" name="confirm">Создать</button>
                        </form>


                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    function submitNewData(id) {
        let value = $('#select_center_' + id).val();
        if (value !== '') {
            $.post("{!! $module_url  !!}&tab=user_without_centers", {userid: id, medcenter: value})
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
            $.post("{!! $module_url  !!}&tab=medical_center", {userid: id, status: value})
                .done(function (data) {
                    location.reload();
                });
        }
    }
</script>
<script>
    $(function () {
        $('[data-mask]').inputmask();
    });

</script>
<script type="application/javascript">
    function edit_med_center(id) {

        let name = $('#med_center_' + id).html()
        let person = prompt("Укажите новое имя для медцентра", name);
        if (person !== null) {
            $.post("{!! $module_url  !!}&tab=medical_center", {id: id, new_name: person})
                .done(function (data) {
                    $('#med_center_' + id).html(person)
                });
        }
    }
</script>
<script type="application/javascript">
    function delete_med_center(id) {

        let name = $('#med_center_' + id).html()
        if (confirm("Вы точно желаете удалить " + name + "'?")) {

            $.post("{!! $module_url  !!}&tab=medical_center", {delete_center_id: id})
                .done(function (data) {
                    location.reload();
                });

        }
    }
</script>
<script type="application/javascript">
    function delete_user(id) {

        let name = $('#user_' + id).html()
        if (confirm("Вы точно желаете удалить '" + name + "'?")) {

            $.post("{!! $module_url  !!}&tab=medical_center", {delete_user_id: id})
                .done(function (data) {
                    location.reload();
                });

        }
    }
</script>
</body>
</html>
