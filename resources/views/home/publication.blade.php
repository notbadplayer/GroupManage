<div class="col-12">
    <div class="card">
        <div class="card-body card-body-dashboard">
            <h5 class="card-title">{{ $publication->name }}</h5>
            {!! $publication->content !!}
            @if(isset($publication->questionnaire))
            <hr>
            <p class="card-text">{{ $publication->questionnaire->description }}</p>

                @if($publication->questionnaire->type == 'closed')
                    @include('home.questionnaireClosed')
                @endif
            @endif
        </div>
    </div>
</div>
