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
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item"> <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Podgląd</button></li>
                                <li class="nav-item"> <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-edit" id="button-profile-edit">Aktualizuj dane</button>
                                </li>
                                <li class="nav-item"> <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-settings">Grupy</button></li>
                                <li class="nav-item"> <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password" id="button-password-edit">Zmień
                                        hasło</button></li>
                            </ul>
                            <div class="tab-content pt-2 profile">
                                <div class="tab-pane fade show active profile-overview profile-edit" id="profile-overview">
                                    <h5 class="card-title">Podgląd profilu</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Imię:</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Nazwisko:</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->surname }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">e-mail:</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">telefon:</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->phone ?? '' }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Grupy:</div>
                                        <div class="col-lg-9 col-md-8">
                                            <ul class="list-unstyled">
                                                @if (Gate::check('admin-level'))
                                                    <li class="">Administrator</li>
                                                @else
                                                    @foreach (Auth::user()->groups->unique() as $group)
                                                        @if (count($group->subgroups->whereIn('id', Auth::user()->subgroups->pluck('id'))) > 0)
                                                            @foreach ($group->subgroups->whereIn('id', Auth::user()->subgroups->pluck('id')) as $subgroup)
                                                                <li class="">{{ $group->name }}-{{ $subgroup->name }}
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li class="">{{ $group->name }}</li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">
                                            <a class="btn btn-outline-primary mt-2" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                                                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Wyloguj
                                                    się</span>
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>


                                        </div>
                                        <div class="col-lg-9 col-md-8">


                                        </div>


                                    </div>

                                </div>
                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                    <form method="post" action="{{ route('users.profileUdate', $user->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Imię:</label>
                                            <div class="col-md-8 col-lg-9"> <input type="text"
                                                    class="form-control @error('name')is-invalid @enderror" name="name"
                                                    id="name" value="{{ old('name', $user->name ?? '') }}">
                                                @if ($errors->has('name'))
                                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="surname" class="col-md-4 col-lg-3 col-form-label">Nazwisko:</label>
                                            <div class="col-md-8 col-lg-9"><input type="text"
                                                    class="form-control @error('surname')is-invalid @enderror"
                                                    name="surname" id="surname"
                                                    value="{{ old('surname', $user->surname ?? '') }}">
                                                @if ($errors->has('surname'))
                                                    <div class="invalid-feedback">{{ $errors->first('surname') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="company" class="col-md-4 col-lg-3 col-form-label">e-mail:</label>
                                            <div class="col-md-8 col-lg-9"> <input type="email"
                                                    class="form-control @error('email')is-invalid @enderror" name="email"
                                                    id="email" value="{{ old('email', $user->email ?? '') }}">
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="Job" class="col-md-4 col-lg-3 col-form-label">telefon:</label>
                                            <div class="col-md-8 col-lg-9"> <input type="text"
                                                    class="form-control @error('phone')is-invalid @enderror"
                                                    name="phone" id="phone"
                                                    value="{{ old('phone', $user->phone ?? '') }}">
                                                @if ($errors->has('phone'))
                                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-center"> <button type="submit" class="btn btn-primary">Zapisz
                                                zmiany</button></div>
                                    </form>
                                </div>
                                <div class="tab-pane fade pt-3" id="profile-settings">
                                    <div class="row mb-3 profile-edit">
                                        <label for="phone" class="col-sm-2 col-form-label fw-bold">Członek
                                            grupy:</label>
                                        <div class="col-sm-10" id="userGroupsField">
                                            @if (isset($user))
                                                @foreach ($user->groups->unique() as $group)
                                                    <button type="button" class="btn btn-success mb-2 me-2">
                                                        {{ $group->name }}</button>
                                                @endforeach
                                                @foreach ($user->subgroups as $subgroup)
                                                    <button type="button" class="btn btn-success mb-2 me-2">
                                                        {{ $subgroup->group->name }} <span
                                                            class="badge bg-white text-success ms-1">{{ $subgroup->name }}</span></button>
                                                @endforeach
                                            @endif
                                            <button type="button" id="button-addUserToGroup"
                                                class="btn btn-outline-success mb-2 me-2"><i
                                                    class="fa-solid fa-people-group me-2"></i>Dołącz do
                                                grupy</button>
                                            <button type="button" id="button-removeUserFromGroup"
                                                class="btn btn-outline-danger mb-2 me-2"><i
                                                    class="fa-solid fa-user-minus me-2"></i>Wypisz się z grupy</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <form method="post" action="{{ route('users.passwordUpdate') }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Obecne
                                                hasło:</label>
                                            <div class="col-md-8 col-lg-9"> <input name="current_password"
                                                    type="password"
                                                    class="form-control @error('current_password')is-invalid @enderror"
                                                    id="current_password" value="{{ old('current_password') }}">
                                                @if ($errors->has('current_password'))
                                                    <div class="invalid-feedback">{{ $errors->first('current_password') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="password" class="col-md-4 col-lg-3 col-form-label">Nowe
                                                hasło:</label>
                                            <div class="col-md-8 col-lg-9"> <input name="password" type="password"
                                                    class="form-control @error('password')is-invalid @enderror"
                                                    id="password" value="{{ old('password') }}">
                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="password_confirmation"
                                                class="col-md-4 col-lg-3 col-form-label">Powtórz hasło:</label>
                                            <div class="col-md-8 col-lg-9"> <input name="password_confirmation"
                                                    type="password"
                                                    class="form-control @error('password')is-invalid @enderror"
                                                    id="password_confirmation"
                                                    value="{{ old('password_confirmation') }}"></div>
                                        </div>
                                        <div class="text-center"> <button type="submit" class="btn btn-primary">Zmień
                                                hasło</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>



    <script type="module">

@if($errors->has('name') || ($errors->has('surname')) || ($errors->has('email')) || ($errors->has('phone')))
$('#button-profile-edit').click();

@endif

@if($errors->has('current_password') || ($errors->has('password')) || ($errors->has('password_confirmation')))
$('#button-password-edit').click();

@endif



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
    var newMember = "{{ $user->id }}";

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



//Kliknięciee przycisku "Wypisz się z grupy":
$('#button-removeUserFromGroup').on( 'click', function () {

    let htmlRemoveFromGroupText = '<select class="form-select members" name="removeFromGroup" id="removeFromGroup" value="" style="width: 100%"><option value="">Wybierz Grupę</option>@foreach($user->groups->unique() ?? [] as $userGroup)<option value="{{ $userGroup->id }}">{{ $userGroup->name }}</option>@endforeach</select></br>';


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
    var $member = "{{ $user->id }}";


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
                window.location = "{{ route('users.profile') }}"

            },
            error: function(data) {
                console.log('error');
            }
        })

}





    </script>

    {{-- Wyświetlenie paska statusu - pomyślna aktualizacja/ dodanie użytkownika --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif

@endsection
