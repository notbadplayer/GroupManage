<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="QuestionnaireClosed_{{ $publication->questionnaire->id }}"
        id="questionnaire1_{{ $publication->questionnaire->id }}" value="1"
        @if (count($publication->questionnaire->userAnswers) > 0) disabled @endif style="transform: scale(1.5);"
        @if (isset($publication->questionnaire->userAnswers->first()->value) &&
                $publication->questionnaire->userAnswers->last()->value == '1') checked @endif>
    <label class="form-check-label ms-1" for="questionnaire1">Tak</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input ms-1" type="radio" name="QuestionnaireClosed_{{ $publication->questionnaire->id }}"
        id="questionnaire2_{{ $publication->questionnaire->id }}" value="0"
        @if (count($publication->questionnaire->userAnswers) > 0) disabled @endif style="transform: scale(1.5);"
        @if (isset($publication->questionnaire->userAnswers->first()->value) &&
                $publication->questionnaire->userAnswers->first()->value == '0') checked @endif>
    <label class="form-check-label ms-2" for="questionnaire2">Nie</label>
</div>
<div class="form-check form-check-inline">
    <a class="btn btn-sm btn-outline-primary vote @if (count($publication->questionnaire->userAnswers) > 0) invisible @endif"
        id="vote_{{ $publication->questionnaire->id }}" role="button"
        data-value="{{ $publication->questionnaire->id }}">
        <i class="fa-solid fa-check me-1"></i> Wyślij <span class="d-none d-sm-inline">odpowiedź</span>
    </a>
</div>
<div id="QuestionnaireInvalidFeedback_{{ $publication->questionnaire->id }}"></div>

@if (count($publication->questionnaire->userAnswers) > 0)
    <div>
        Udzielono już odpowiedzi na to pytanie:
        {{ $publication->questionnaire->userAnswers->first()->value ? 'Tak' : 'Nie' }}
    </div>
@endif
