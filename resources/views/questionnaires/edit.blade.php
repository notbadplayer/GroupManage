<div class="accordion-item">
    <h2 class="accordion-header" id="headingQuestionnaire">
        <button class="accordion-button collapsed" type="button" id="questionnairesButton" data-bs-toggle="collapse"
            data-bs-target="#collapseQuestionnaire" aria-expanded="false" aria-controls="collapseTwo">
            Ankiety
        </button>
    </h2>
    <div id="collapseQuestionnaire" class="accordion-collapse collapse" aria-labelledby="headingQuestionnaire"
        data-bs-parent="#publicationAccordion">
        <div class="accordion-body">

            @if (isset($publication->questionnaire) && count($publication->questionnaire->answers) > 0)
                <a class="btn btn-outline-primary mb-3" id="questionnaireResults"
                    href="{{ route('questionnaires.results', ['Questionnaire' => $publication->questionnaire->id]) }}"
                    role="button">
                    Zobacz wyniki
                </a>
            @endif

            @if (isset($publication) && !($publication->archived))
                <a class="btn btn-outline-primary mb-3" id="addRemoveQuestionnaire"
                    data-available="{{ isset($publication->questionnaire) && $publication->questionnaire ? '1' : '0' }}"
                    data-bs-toggle="collapse" href="#collapse1" role="button" aria-expanded="false"
                    aria-controls="collapse1">

                    @if (isset($publication->questionnaire))
                        Usuń ankietę
                    @else
                        Dodaj ankietę
                    @endif

                </a>
            @else
                <div>Nie można edytować ankiety w archiwalnym ogłoszeniu.</div>
            @endif
            <div class="collapse @if (isset($publication->questionnaire)) show @endif" id="collapse1">
                <input type="hidden" id="questionnaireAvailable" name="questionnaireAvailable"
                    value="{{ isset($publication->questionnaire) && $publication->questionnaire ? '1' : '0' }}">

                <div class="row mb-3 mt-3">
                    <label for="questionnaireDescription" class="col-sm-2 col-form-label">Opis:</label>
                    <div class="col-sm-10"> <input type="text"
                            class="form-control @error('questionnaireDescription')is-invalid @enderror"
                            name="questionnaireDescription"
                            value="{{ old('questionnaireDescription', $publication->questionnaire->description ?? '') }}"
                            @if (isset($publication) && $publication->archived) disabled @endif>
                        @if ($errors->has('questionnaireDescription'))
                            <div class="invalid-feedback">{{ $errors->first('questionnaireDescription') }}</div>
                        @endif
                    </div>

                </div>
                <div class="row mb-3">
                    <label for="questionnaireDate" class="col-sm-2 col-form-label">Ważna do:</label>
                    <div class="col-sm-10"> <input type="date" class="form-control" name="questionnaireDate"
                            value="{{ old('questionnaireDate', $publication->questionnaire->validTill ?? $questionnaireValidTill) }}"
                            @if (isset($publication) && $publication->archived) disabled @endif>
                    </div>
                </div>

                <fieldset class="row mb-3 @if (isset($publication->questionnaire)) d-none @endif "
                    id="questionnaireTypeFields">
                    <legend class="col-form-label col-sm-2 pt-0">Typ odpowiedzi:</legend>
                    <div class="col-sm-10">
                        <div class="form-check"> <input class="form-check-input" type="radio" name="questionnaireType"
                                id="questionnaireType1" value="closed" @if (old('questionnaireType') == 'closed' || ($publication->questionnaire->type ?? 'closed') == 'closed') checked @endif
                                @if (isset($publication) && $publication->archived) disabled @endif>
                            <label class="form-check-label" for="questionnaireType1"> tak/nie </label>
                        </div>
                        <div class="form-check"> <input class="form-check-input" type="radio" name="questionnaireType"
                                id="questionnaireType2" value="open" @if (old('questionnaireType') == 'open' || ($publication->questionnaire->type ?? '') == 'open') checked @endif
                                @if (isset($publication) && $publication->archived) disabled @endif>
                            <label class="form-check-label" for="questionnaireType2"> otwarta odpowiedź </label>
                        </div>
                    </div>
                </fieldset>


            </div>


        </div>
    </div>
</div>

<script type="module">
$('#addRemoveQuestionnaire').on('click', function(){
    if($( this ).data('available') == '0') {

        $( this ).data('available', '1');
        $('#questionnaireAvailable').val('1')
        $('#addRemoveQuestionnaire').text('Usuń ankietę')
    } else {
        removeQuestionnaireConfirmation();
    }
});


@if ($errors->has('questionnaireDescription') || $errors->has('questionnaireDate') || old('questionnaireDescription'))
    $('#questionnairesButton').click();
    $('#addRemoveQuestionnaire').click();
    $('#collapse1').addClass('show');

@endif

function removeQuestionnaireConfirmation()
{
    @if(isset($publication->questionnaire))

    Swal.fire({
            title: 'Usunąć ankietę?',
            icon: 'warning',
            text: 'Uwaga, usunięcie istniejącej ankiety spowoduje trwałą utratę jej wyników.',
            confirmButtonText: 'Tak, usuń',
            confirmButtonColor: '#dc3545',
            showDenyButton: 'true',
            denyButtonText: 'Anuluj',
            denyButtonColor: '#0d6efd',
            }).then((result) =>
                {
                    if(result.isConfirmed){
                        //removeQuestionnaire(result.value);
                        removeQuestionnaire();

                    }else if (result.isDenied) {
                        $('#collapse1').addClass('show');
                    }
                }
            );


    function removeQuestionnaire(){
                let url = "{{ route('questionnaires.destroy', ['Questionnaire' => $publication->questionnaire->id ?? null]) }}";
                const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });

            $.ajax({
                    url: url,
                    method: 'POST',
                    success: function(data) {
                        $('#addRemoveQuestionnaire').data('available', '0');
                        $('#questionnaireAvailable').val('0')
                        $('#addRemoveQuestionnaire').text('Dodaj ankietę')
                        $('#questionnaireTypeFields').removeClass('d-none')

                        Toast.fire({
                            icon: 'success',
                            title:  ("Usunięto ankietę")
                            })
                    },
                    error: function(data) {
                        Toast.fire({
                            icon: 'error',
                            title: ("Błąd usuwania ankiety")
                            })
                    }
                })

            }

    @endif

}

</script>
