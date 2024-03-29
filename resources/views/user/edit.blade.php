@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Użytkownicy</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Użytkownicy</a></li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mw-90">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight card-title">
                                    @if (isset($user))
                                        Edycja Użytkownika
                                    @else
                                        Dodawanie użytkownika
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ url()->previous() }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-chevron-left me-sm-2"></i><span
                                                class="d-none d-sm-inline">Powrót<span></button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
                                    @csrf
                                    @if (isset($user))
                                        @method('PUT')
                                    @endif

                                    <div class="row mb-3 profile-edit">
                                        <label for="name" class="col-sm-2 col-form-label fw-bold">Imię:</label>
                                        <div class="col-sm-10"> <input type="text"
                                                class="form-control @error('name')is-invalid @enderror" name="name"
                                                id="name" value="{{ old('name', $user->name ?? '') }}">
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="surname" class="col-sm-2 col-form-label fw-bold">Nazwisko:</label>
                                        <div class="col-sm-10 "> <input type="text"
                                                class="form-control @error('surname')is-invalid @enderror" name="surname"
                                                id="surname" value="{{ old('surname', $user->surname ?? '') }}">
                                            @if ($errors->has('surname'))
                                                <div class="invalid-feedback">{{ $errors->first('surname') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="email" class="col-sm-2 col-form-label fw-bold">Email:</label>
                                        <div class="col-sm-10"> <input type="email"
                                                class="form-control @error('email')is-invalid @enderror" name="email"
                                                id="email" value="{{ old('email', $user->email ?? '') }}">
                                            @if ($errors->has('email'))
                                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="phone" class="col-sm-2 col-form-label fw-bold">Telefon:</label>
                                        <div class="col-sm-10"> <input type="text"
                                                class="form-control @error('phone')is-invalid @enderror" name="phone"
                                                id="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                            @if ($errors->has('phone'))
                                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    @isset($user)
                                        <div class="row mb-3 profile-edit">
                                            <label for="phone" class="col-sm-2 col-form-label fw-bold">Członek grupy:</label>
                                            <div class="col-sm-10" id="userGroupsField">
                                                @if (isset($user))
                                                    @foreach ($user->groups->unique() as $group)
                                                        <a href="{{ route('groups.edit', $group->id) }}"><button type="button"
                                                                class="btn btn-success mb-2 me-2">
                                                                {{ $group->name }}</button></a>
                                                    @endforeach
                                                    @foreach ($user->subgroups as $subgroup)
                                                        <a href="{{ route('subgroups.edit', $subgroup->id) }}"><button
                                                                type="button" class="btn btn-success mb-2 me-2">
                                                                {{ $subgroup->group->name }} <span
                                                                    class="badge bg-white text-success ms-1">{{ $subgroup->name }}</span></button></a>
                                                    @endforeach
                                                @endif
                                                <button type="button" id="button-addUserToGroup"
                                                    class="btn btn-outline-success mb-2 me-md-2"><i
                                                        class="fa-solid fa-people-group me-md-2"></i>Dodaj do
                                                    grupy</button>
                                                @if (count($user->groups) > 0)
                                                    <button type="button" id="button-removeUserFromGroup"
                                                        class="btn btn-outline-danger mb-2 me-md-2"><i
                                                            class="fa-solid fa-user-minus me-md-2"></i>Wypisz z grupy</button>
                                                @endif
                                            </div>
                                        </div>
                                    @endisset

                                    <div class="float-end mb-3 mt-3">
                                        @isset($user)
                                            <a class="btn btn-outline-danger me-3" id="buttonRemoveUser"><i
                                                    class="fa-solid fa-user-xmark me-1"></i>Usuń</a>
                                        @endisset
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-check me-1"></i>Zapisz</button>


                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @isset($user)
                <form method="post" action="{{ route('users.destroy', ['User' => $user->id]) }}" id="deleteUserForm">
                    @csrf
                </form>
            @endisset

        </section>

    </main>

    {{-- Generowanie tabeli uczestników --}}
    <script type="module">

//Kliknięciee przycisku "dopisz do grupy":
$('#button-addUserToGroup').on( 'click', function () {

    let htmlAddToGroupText = '<select class="form-select members" name="newGroup" id="newGroup" value="" style="width: 80%"><option value="">Wybierz Grupę</option>@foreach($groups ?? [] as $group)<option value="{{ $group->id }}">{{ $group->name }}</option>@endforeach</select></br>';
    htmlAddToGroupText = htmlAddToGroupText + '<div id="subgroupsField" class="invisible"><div class="mt-5">Dodaj również do podgrupy:</div>';
    htmlAddToGroupText = htmlAddToGroupText + '<select class="form-select subgroups" name="newSubgroup" id="newSubgroup" value="" style="width: 80%"><option value="">Nie wybrano</option></select></br></div>';

    Swal.fire({
                    title: 'Dopisz użytkownika do grupy: ',
                    html: htmlAddToGroupText,
                    icon: 'info',
                    confirmButtonText: 'Dodaj',
                    confirmButtonColor: '#0d6efd',
                    showCancelButton: 'true',
                    cancelButtonText: 'Anuluj',
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


;
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
    var newMember = "{{ $user->id ?? '' }}";

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
                        //$('#userGroupsField').prepend('<a href="/groups/edit/'+data['groupId']+'"><button type="button" class="btn btn-success mb-2 me-2">'+data['groupName']+'</button></a>')
                        if(subgroups !== [""]){

                            $('<a href="/subgroups/edit/'+data['subgroupId']+'"><button type="button" class="btn btn-success mb-2 me-2">'+data['groupName']+'<span class="badge bg-white text-success ms-1">'+data['subgroupName']+'</span></button></a>' ).insertBefore( "#button-addUserToGroup" );
                        }

            },
            error: function(data) {
                Toast.fire({
                            icon: 'error',
                            title: ("Błąd dodawania do grupy.")
                            })
            }
        })

}

//Kliknięciee przycisku "Usuń":
$('#buttonRemoveUser').on( 'click', function () {

Swal.fire({
                    title: 'Usuń użytkownika',
                    text: 'Czy chcesz usunąć użytkownika: {{$user->name ?? ''}} {{$user->surname ?? ''}}',
                    icon: 'warning',
                    confirmButtonText: 'Tak, usuń',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: 'true',
                    cancelButtonText: 'Anuluj',
                    }).then((result) =>
                        {
                            if(result.isConfirmed){
                                $('#deleteUserForm').submit();
                            }
                        }
                    );
});


//Kliknięciee przycisku "Wypisz z grupy":
$('#button-removeUserFromGroup').on( 'click', function () {

@if(isset($user))
let htmlRemoveFromGroupText = '<select class="form-select members" name="removeFromGroup" id="removeFromGroup" value="" style="width: 100%"><option value="">Wybierz Grupę</option>@foreach($user->groups->unique() ?? [] as $userGroup)<option value="{{ $userGroup->id }}">{{ $userGroup->name }}</option>@endforeach</select></br>';
@endif

Swal.fire({
                title: 'Odłącz od grupy: ',
                html: htmlRemoveFromGroupText,
                icon: 'warning',
                confirmButtonText: 'Odłącz',
                confirmButtonColor: '#0d6efd',
                showCancelButton: 'true',
                cancelButtonText: 'Anuluj',
                didOpen: (q) => {
                    $('#removeFromGroup').select2({
                    });
                    $('#removeFromGroup').select2({
                        placeholder: "Nie wybrano"
                    });

                }

                }).then((result) =>
                    {
                        //console.log(result)
                        if(result.isConfirmed){
                            removeMemberFromGroup();
                        }
                    }
                );
});

function removeMemberFromGroup(){
var $groupId = $('#removeFromGroup').val();
var $member = "{{ $user->id ?? '' }}";


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});

$.ajax({
        url: "{{ route('groups.removeMember') }}",
        method: 'POST',
        data: {
            group: $groupId,
            member: $member,
        },
        success: function(data) {
            window.location = "{{ route('users.index') }}"

        },
        error: function(data) {
            console.log('error');
        }
    })

}


$('#sidebar-mainpage-nav').addClass('collapsed');
$('#sidebar-users-nav').removeClass('collapsed');
$('#users-nav').addClass('show');
@if(!(isset($user)))
$('#users-nav-add').addClass('active');
@endif

</script>

    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif

@endsection
