<a href="/paymentstests/?s=sudy">Sudy</a>
<a href="/paymentstests/?s=gfs">GFS</a>
<a href="/paymentstests/?s=sudyback">sudyCallbacks</a>
<form action="{{$source}}" method="{{$method}}">
    @foreach($fields as $field=>$value)
        <label for="{{$field}}">{{$field}}
            <input type="text" name="{{$field}}" value="{{$value}}"
                   id="{{$field}}">
        </label>
    @endforeach
    <p>sign control</p>
    <p>{{$sign}}</p>
    <button type="submit">submmit</button>
</form>

{{date('Y:d:m h:i:s')}}

<a href="{{$url}}">{{$url}}</a>