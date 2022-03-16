@switch($service_id)
    @case(179)
        @include('partials.payment_en')
    @break

    @default
        @include('partials.payment')
    @break
@endswitch
