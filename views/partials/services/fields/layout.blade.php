@if(count($fields) == 1)
    <div class="form-group">
        {!! $fields[0] !!}
    </div>
@else
    <div class="row form-row">
        @foreach($fields as $field)
            <div class="col-{{ 12 / count($fields) }} form-group">
                {!! $field !!}
            </div>
        @endforeach

    </div>
@endif