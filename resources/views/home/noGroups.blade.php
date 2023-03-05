<script type="module">
$(document).ready(function () {

    let htmlAddToGroupText = '<select class="form-select members" name="newGroup" id="newGroup" value="" style="width: 80%"><option value="">Wybierz Grupę</option>@foreach($allGroups ?? [] as $group)<option value="{{ $group->id }}">{{ $group->name }}</option>@endforeach</select></br>';
    htmlAddToGroupText = htmlAddToGroupText + '<div id="subgroupsField" class="invisible"><div class="mt-5">Dodaj również do podgrupy:</div>';
    htmlAddToGroupText = htmlAddToGroupText + '<select class="form-select subgroups" name="newSubgroup" id="newSubgroup" value="" style="width: 80%"><option value="">Nie wybrano</option></select></br></div>';


    Swal.fire({
                    title: 'Dopisz się do grupy: ',
                    html: htmlAddToGroupText,
                    icon: 'question',
                    confirmButtonText: 'Dodaj',
                    confirmButtonColor: '#0d6efd',
                    showCancelButton: 'true',
                    cancelButtonText: 'Anuluj',
                    allowOutsideClick: false,
                    didOpen: (q) => {
                        console.log('otwarty swal');
                        $('#newGroup').select2({
                        });
                        $('#newSubgroup').select2({
                            placeholder: "Nie wybrano"
                        });
                        $('#newGroup').on('change', function(){
                            $('#newSubgroup').empty();
                            $('#newSubgroup').append('<option value="">Nie wybrano</option>');
                            var $groupId =  $('#newGroup').val();
                            getSubgroups($groupId);
                        })

                    }

                    }).then((result) =>
                        {
                            //console.log(result)
                            if(result.isConfirmed){
                                addMemberToGroup();
                            }
                        }
                    );

});


function getSubgroups($groupId)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $.ajax({
            url: "/groups/subgroups/"+$groupId,
            method: 'get',
            success: function(data) {
                $('#subgroupsField').removeClass('invisible');

                Object.entries(data).forEach(entry => {
                const [key, value] = entry;
                //console.log(key, value);
                $('#newSubgroup').append("<option value='"+value+"'>"+key+"</option>");
                });
            },
            error: function(data) {
                console.log('błąd pobierania subgroups');
                $('#subgroupsField').addClass('invisible');
            }
        })

}


function addMemberToGroup(){
    var group = $('#newGroup').val();
    var subgroups = [];
    subgroups.push($('#newSubgroup').val());
    var newMember = "{{ Auth::id() }}";


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
            url: "{{ route('groups.addMember') }}",
            method: 'POST',
            data: {
                group: group,
                member: newMember,
                subgroups: subgroups
            },
            success: function(data) {
                Toast.fire({
                            icon: 'success',
                            title:  ("Dodano do grupy")
                            })
                            window.location.href = "{{route('home')}}";
            },
            error: function(data) {
                Toast.fire({
                            icon: 'error',
                            title: ("Błąd dodawania do grupy.")
                            })
            }
        })


    }


</script>
