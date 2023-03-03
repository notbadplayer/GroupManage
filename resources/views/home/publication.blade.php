<div class="col-12">
    <div class="card">
        <div class="card-body card-body-dashboard">
            <h5 class="card-title">{{ $publication->name }}</h5>
            {!! $publication->content !!}
            @if (isset($publication->questionnaire) && (new DateTime($publication->questionnaire->validTill) > new DateTime()))
                <hr>
                <p class="card-text fw-bold text-dark">{{ $publication->questionnaire->description }}</p>
                @if ($publication->questionnaire->type == 'closed')
                    @include('home.questionnaireClosed')
                @elseif ($publication->questionnaire->type == 'open')
                    @include('home.questionnaireOpen')
                @endif
            @endif
        </div>
    </div>
</div>
