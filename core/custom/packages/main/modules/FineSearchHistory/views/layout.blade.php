<!doctype html>
<html lang="en">
<head>
    <title>{{ $moduleName }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="theme-color" content="#1d2023">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <link rel="stylesheet" type="text/css" href="/manager/media/style/common/bootstrap/css/bootstrap.min.css?v=4.0.0-alpha.5" />
    <link rel="stylesheet" type="text/css" href="/manager/media/style/common/font-awesome/css/font-awesome.min.css?v=4.7.0" />


    <link rel="stylesheet" type="text/css" href="/manager/media/style/{{ $managerTheme }}/css/fonts.css?v=1.3.5" />
    <link rel="stylesheet" type="text/css" href="/manager/media/style/{{ $managerTheme }}/css/custom.css?v=1.3.5" />
    <link rel="stylesheet" type="text/css" href="/manager/media/style/{{ $managerTheme }}/css/tabpane.css" />
    <link rel="stylesheet" type="text/css" href="/manager/media/style/{{ $managerTheme }}/css/contextmenu.css" />
    <link rel="stylesheet" type="text/css" href="/manager/media/style/{{ $managerTheme }}/css/main.css" />


    <script type="text/javascript" src="media/script/jquery/jquery.min.js"></script>

    <link rel="stylesheet" href="/assets/lib/codebase/webix.css">
    <link rel="stylesheet" href="/assets/lib/codebase/skins/evo.css">
    <script src="/assets/lib/codebase/webix.js"></script>


</head>
<body>
<div id="actions">
    <div class="btn-group">

        @section('action')
            <a id="Button5" class="btn btn-secondary" href="javascript:;" onclick=" document.location.href = 'index.php?a=76&tab=0';">
                <i class="fa fa-times-circle"></i><span>Отмена</span>
            </a>
        @show
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Истории поиска</h1>
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>