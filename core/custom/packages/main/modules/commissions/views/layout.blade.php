<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="media/style/{{$manager_theme}}/style.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="application/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script type="application/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="application/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<script type="application/javascript">
    let module_url = '{!! $module_url !!}';
    let service_id = {{$service['id']??0}};
</script>
<script type="application/javascript" src="{{ \EvolutionCMS\Main\Support\Helpers::fileWithVersion('theme/js/manager/commissions.js') }}"></script>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach($breadcrumbs as $breadcrumb)
                @if(!$loop->last)
                    <li class="breadcrumb-item">
                        <a href="{{$breadcrumb['url']}}">{{$breadcrumb['title']}}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{$breadcrumb['title']}}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>

    @yield('content')

</div>

</body>
</html>
