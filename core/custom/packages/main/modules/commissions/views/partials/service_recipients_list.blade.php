@if(!empty($_service_recipients))
    <div class="list-group mt-5">
        <h3>Основні платежі</h3>
        @foreach($_service_recipients as $service_recipient)
            <div class="list-group-item list-group-item-action">
                <a href="{{$_module_url}}&action=service_recipient&service_recipient_id={{$service_recipient['id']}}"
                   class="badge-light p-1"
                   data-service_recipient_id="{{$service_recipient['id']}}">
                    {{$service_recipient['recipient_name']}}
                </a>
                <span>[{{$service_recipient['sum']}}]</span>
                <button class="btn btn-danger" type="button"
                        onclick="delete_service_recipient({{$service_recipient['id']}})">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
        @endforeach
    </div>
@endif