<script type="module">

    $('.vote').on('click', function(){
         var $questionnaireId = $( this ).data('value');
        var radioValue = $("input[name='QuestionnaireClosed_"+$questionnaireId+"']:checked").val();
        if(!radioValue){
            $('#QuestionnaireInvalidFeedback_'+$questionnaireId).append('<span class="text-danger">Musisz wybrać jedną opcję.</span>')
        } else{
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
                        url: "questionnaire/vote/"+$questionnaireId,
                        method: 'POST',
                        data: {
                            value: radioValue,
                        },
                        success: function(data) {
                            disableFields();
                            Toast.fire({
                                icon: 'success',
                                title:  ("Wysłano odpowiedź")
                                })
                        },
                        error: function(data) {
                            Toast.fire({
                                icon: 'error',
                                title: ("Błąd wysyłania odpowiedzi")
                                })
                        }
                    })

        }

        function disableFields()
        {
            $('#vote_'+$questionnaireId).addClass("invisible");
            $('#questionnaire2_'+$questionnaireId).attr("disabled", true);
            $('#questionnaire1_'+$questionnaireId).attr("disabled", true);
        }

    });


//Logika kliknięcia w przycisz "Dodaj wiersz" -  w przypadku ankiety otwartej
$('.QuestionnaireAddOption').on('click', function(){

var html = '<input id="modalQuestionnaireValue" class="form-control col-12">';
var questionnaireId = $( this ).data('questionnaire-id');

    Swal.fire({
                title: 'Dodaj odpowiedź:',
                html: html,
                confirmButtonText: 'Zapisz',
                confirmButtonColor: '#0d6efd',
                showCancelButton: 'true',
                cancelButtonText: 'Anuluj',
                }).then((result) =>
                    {
                        if(result.isConfirmed){
                            sendOption(questionnaireId);
                        }
                    }
                );
})

function sendOption(questionnaireId){

    var value = $('#modalQuestionnaireValue').val()
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
            url: "{{ route('questionnaires.addOption') }}",
            method: 'POST',
            data: {
                questionnaire: questionnaireId,
                value: value,
            },
            success: function(data) {
                $("#questionnaireOpenAnswersFieldList_"+questionnaireId).append('<li id="questionnaireOpenAnswersElement_'+data.answerId+'"><div class="row justify-content-between"><span class="col">'+value+'</span><span class="col text-end"><button type="button" class="btn btn-sm btn-outline-danger QuestionnaireEditOption" data-answer-id="'+ data.answerId+'"data-questionnaire-value="'+value+'"data-questionnaire-id="'+ data.questionnaireId+'"><i class="fa-solid fa-pencil"></i><span class="d-none d-md-inline ms-2">Edytuj</span></button></span></span></div></li>');
                Toast.fire({
                                icon: 'success',
                                title:  ("Wysłano odpowiedź")
                                })
            },
            error: function(data) {
                Toast.fire({
                                icon: 'error',
                                title: ("Błąd wysyłania odpowiedzi")
                                })
            }
        })

}

//kliknięcie w przycisk 'Edytuj" przy opcji dodanej przez użytkownika

function editOption(data)
{
    var $answerId = data.data('answer-id');
    var $questionnaireId = data.data('questionnaire-id');

    var html = '<input id="modalQuestionnaireEditValue" class="form-control col-12" value="'+ data.data('questionnaire-value')+'">';
    Swal.fire({
                title: 'Edycja odpowiedzi',
                icon: 'question',
                html: html,
                confirmButtonText: 'Zapisz',
                confirmButtonColor: '#0d6efd',
                showCancelButton: 'true',
                cancelButtonText: 'Anuluj',
                showDenyButton: true,
                denyButtonText: 'Usuń',
                }).then((result) =>
                    {
                        if(result.isConfirmed){
                            updateAnswer($answerId, $('#modalQuestionnaireEditValue').val())
                        }else if (result.isDenied) {
                            confirmDeleteUserAnswer($answerId, $questionnaireId);
                        }
                    }
                );
}

function updateAnswer($answerId, $value){
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
            url: "{{ route('questionnaires.updateOption') }}",
            method: 'POST',
            data: {
                answer: $answerId,
                value: $value,
            },
            success: function(data) {
                 $("#col_text_"+$answerId).text($value);
                 $("#buttonEdit_"+$answerId).data('questionnaire-value', $value);
                Toast.fire({

                                icon: 'success',
                                title:  ("Aktualizowano odpowiedź")
                                })
            },
            error: function(data) {
                Toast.fire({
                                icon: 'error',
                                title: ("Błąd aktualizowania odpowiedzi")
                                })
            }
        })
}

//kliknięcie w przycisk edycji odpowiedzi użytkownika
$(document).on('click',".QuestionnaireEditOption", function(){
editOption($( this ));
});

function confirmDeleteUserAnswer($answerId, $questionnaireId){
    Swal.fire({
                title: 'Usuwanie odpowiedzi',
                icon: 'warning',
                text: 'Czy na pewno usunąć odpowiedź?',
                confirmButtonText: 'Usuń',
                confirmButtonColor: '#dc3545',
                showCancelButton: 'true',
                cancelButtonText: 'Anuluj',
                }).then((result) =>
                    {
                        if(result.isConfirmed){
                            deleteUserOption($answerId, $questionnaireId)
                        }
                    }
                );
}

function deleteUserOption($answerId, $questionnaireId)
{
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
            url: "{{ route('questionnaires.deleteOption') }}",
            method: 'POST',
            data: {
                answer: $answerId,
            },
            success: function(data) {
                $("#questionnaireOpenAnswersElement_"+$answerId).remove();
                Toast.fire({
                                icon: 'success',
                                title:  ("Usunięto odpowiedź")
                                })
            },
            error: function(data) {
                Toast.fire({
                                icon: 'error',
                                title: ("Błąd usuwania odpowiedzi")
                                })
            }
        })
}



    </script>
