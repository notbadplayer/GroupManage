<div>
    <ol id="questionnaireOpenAnswersFieldList_{{ $publication->questionnaire->id }}">
        @foreach ($publication->questionnaire->answers as $answer)
            <li id="questionnaireOpenAnswersElement_{{$answer->id}}">
                <div class="row justify-content-between">
                    <span class="col-9" id="col_text_{{$answer->id}}">{{ $answer->userValue }}</span>
                    @if ($answer->user_id == Auth::user()->id)
                        <span class="col-3 text-end">


                            <button type="button" class="btn btn-sm btn-outline-danger QuestionnaireEditOption" id="buttonEdit_{{ $answer->id}}"
                                data-answer-id="{{ $answer->id}}"
                                data-questionnaire-value="{{ $answer->userValue }}"
                                data-questionnaire-id="{{ $publication->questionnaire->id }}"><i
                                    class="fa-solid fa-pencil"></i><span class="d-none d-md-inline ms-2">Edytuj</span></button>
                        </span>
                    @endif

        @endforeach
    </ol>
</div>
<div>
    <button type="button" class="btn btn-outline-primary QuestionnaireAddOption"
        id="QuestionnaireAddOption_{{ $publication->questionnaire->id }}"
        data-questionnaire-id="{{ $publication->questionnaire->id }}"><i class="fa-solid fa-plus me-2"></i>Dodaj
        wiersz</button>
</div>
