<script type="module">

    $('.vote').on('click', function(){
         var $questionnaireId = $( this ).data('value');
        var radioValue = $("input[name='QuestionnaireClosed_"+$questionnaireId+"']:checked").val();
        console.log(radioValue);
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

    </script>
