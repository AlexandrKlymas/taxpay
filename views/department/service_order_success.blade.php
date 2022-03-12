@switch($service_id)
    @case(179)
        @include('partials.services.success_page.content_en')
    @break

    @default
        @include('partials.services.success_page.content')
@endswitch
