@extends('layout.default')

@section('content')


    <h1>{{ $documentObject['pagetitle'] }}</h1>

    <div class="accordion" id="accordion">


        @foreach($faqGroups as $group)
            <h4>{{ $group['row_value'] }}</h4>
            @foreach($group['children'] as $answerAndQuestion)
                <div class="accordion-card">
                    <div class="card-header">
                        <h3>
                            <button class="collapsed" type="button" data-toggle="collapse" data-target="#collapse-{{ $loop->parent->iteration }}-{{ $loop->iteration }}"
                                    aria-expanded="false" aria-controls="collapse-1">
                                {{ $answerAndQuestion['question'] }}
                                <i class="arrow"></i>
                            </button>
                        </h3>
                    </div>

                    <div id="collapse-{{ $loop->parent->iteration }}-{{ $loop->iteration }}" class="collapse" data-parent="#accordion">
                        <div class="text-wrap card-body">{!! $answerAndQuestion['answer'] !!}</div>
                    </div>
                </div>
            @endforeach
        @endforeach


    </div>




@endsection