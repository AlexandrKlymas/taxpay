@extends('Modules.FineSearchHistory::layout')
@section('content')


    <div id="webix-container"></div>
    <div id="pager"></div>

    <script>

        var moduleConfig = {
            url: '{!! $moduleUrl !!}'
        };


    </script>
    <script src="/assets/modules/FineSearchHistory/app.js?v={{ filemtime(MODX_BASE_PATH."assets/modules/FineSearchHistory/app.js") }}"></script>
@endsection