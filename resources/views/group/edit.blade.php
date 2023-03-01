@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Grupy</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('groups.index') }}">Grupy</a></li>
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
                                    @if (isset($group))
                                        Edycja Grupy
                                    @else
                                        Dodawanie grupy
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('groups.index') }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Powrót</button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($group) ? route('groups.update', $group->id) : route('groups.store') }}">
                                    @csrf
                                    @if (isset($group))
                                        @method('PUT')
                                    @endif

                                    <div class="row mb-3 profile-edit">
                                        <label for="name" class="col-sm-2 col-form-label fw-bold">Nazwa:</label>
                                        <div class="col-sm-10"> <input type="text"
                                                class="form-control @error('name')is-invalid @enderror" name="name"
                                                id="name" value="{{ old('name', $group->name ?? '') }}">
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="description" class="col-sm-2 col-form-label fw-bold">Opis:</label>
                                        <div class="col-sm-10 "> <input type="text"
                                                class="form-control @error('description')is-invalid @enderror"
                                                name="description" id="description"
                                                value="{{ old('description', $group->description ?? '') }}">
                                            @if ($errors->has('description'))
                                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (isset($group))
                                        <div class="row mb-3 profile-edit">
                                            <label for="description" class="col-sm-2 col-form-label fw-bold">Grupy
                                                podległe:</label>
                                            <div class="col-sm-10 ">
                                                @foreach ($group->subgroups as $subgroup)
                                                    <a href="{{ route('subgroups.edit', $subgroup->id) }}"><button
                                                            type="button" class="btn btn-success mb-2 me-2">
                                                            {{ $subgroup->name }} <span
                                                                class="badge bg-white text-success ms-1">{{ count($subgroup->members()) }}</span></button></a>
                                                @endforeach
                                                <a href="{{ route('subgroups.create', $group->id) }}"><button
                                                        type="button" class="btn btn-outline-success mb-2 me-2"><i
                                                            class="fa-solid fa-people-group me-2"></i>Dodaj</button></a>

                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($group))
                                        <div class="p-2 flex-grow-1 bd-highlight card-title">
                                            Lista uczestników
                                        </div>
                                        <div class="mt-2 ">
                                            <table class="table tabela table-hover" id="tabela" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Imię</th>
                                                        <th scope="col">Nazwisko</th>
                                                        <th scope="col">Grupa</th>
                                                        <th scope="col">E-mail</th>
                                                        <th scope="col">Telefon</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    @if (isset($group))
                                    <div class="float-start mb-3 mt-3"> <div class="btn btn-outline-success" id="button-addToGroup"><i class="fa-solid fa-user-plus me-1"></i>Dopisz do grupy</div></div>
                                    @endif

                                    <div class="float-end mb-3 mt-3"> <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-check me-1"></i>Zapisz</button></div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>

    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif


    {{-- Generowanie tabeli uczestników --}}
    <script type="module">
    //generowanie tabeli
    let ajaxUrl = "{{ route('groups.members', $group->id ?? null) }}"
            $(function () {
                var table = $('.tabela').DataTable({
                    language: {
                        url: "{{asset('pl.json')}}",
                    },
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    order: [[3, 'desc'], [2,'asc']],
                    responsive: {
            details: false
                    },


                    ajax: ajaxUrl,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'user.name', name: 'user.name', orderable: false,},
                        {data: 'user.surname', name: 'user.surname', orderable: false,},
                        {data: 'subgroup.name', defaultContent: 'brak'},
                        {data: 'user.email', name: 'user.email',orderable: false,},
                        {data: 'user.phone', name: 'user.phone',orderable: false,},

                    ],
                    rowGroup: {
                        dataSrc: 'subgroup.name',
                        emptyDataGroup: 'Brak przypisanej grupy'
                    }
                });

            });


            //kliknięcie w wiersz
            $(document).ready(function () {

            var table = $('#tabela').DataTable();

            $('#tabela tbody').on( 'click', 'tr', function () {
                //console.log( table.row( this ).data().id );
                var data = table.row( this ).data()

                var htmlText = "";
                htmlText = htmlText + '<div class="container mb-3"><div class="row"><div class="col-1 col-md-2"><i class="fa-solid fa-phone"></i></div><div class="col-11 col-md-8">'+data.user.phone+'</div></div></div>'
                htmlText = htmlText + '<div class="container mb-3"><div class="row"><div class="col-1 col-md-2"><i class="fa-solid fa-at"></i></div><div class="col-11 col-md-8">'+data.user.email+'</div></div></div>'

                Swal.fire({
                    title: data.user.name +' '+ data.user.surname,
                    html: htmlText,
                    icon: 'info',
                    confirmButtonText: 'Edytuj dane',
                    confirmButtonColor: '#0d6efd',
                    showCancelButton: 'true',
                    cancelButtonText: 'Anuluj',
                    }).then((result) =>
                        {
                            //console.log(result)
                            if(result.isConfirmed){
                                //window.location.href = "{{route('users.index')}}";
                                window.location.href = "/users/edit/"+data.id;
                            }
                        }
                    );

} );


//Kliknięciee przycisku "dopisz do grupy":
$('#button-addToGroup').on( 'click', function () {

    let htmlAddToGroupText = '<select class="form-select members" name="newMember" id="newMember" value="" style="width: 80%"><option value="null">Wybierz Użytkownika</option>@foreach($users ?? [] as $user)<option value="{{ $user->id }}">{{ $user->name }} {{$user->surname }}</option>@endforeach</select></br>';
    htmlAddToGroupText = htmlAddToGroupText + '<div class="mt-5">Dodaj również do podgrupy:</div>';
    htmlAddToGroupText = htmlAddToGroupText + '<select class="form-select subgroups" name="newMemberSubgroups[]" multiple="multiple" id="newMemberSubgroups" value="" style="width: 80%">@foreach($group->subgroups ??[] as $subgroup)<option value="{{ $subgroup->id }}">{{ $subgroup->name }}</option>@endforeach</select></br>';

    Swal.fire({
                    title: 'Dopisz użytkownika do grupy: {{$group->name ?? ''}}',
                    html: htmlAddToGroupText,
                    iconHtml: '<i class="bi bi-person-add"></i>',
                    iconColor: '#0d6efd',
                    confirmButtonText: 'Zapisz',
                    confirmButtonColor: '#0d6efd',
                    showCancelButton: 'true',
                    cancelButtonText: 'Anuluj',
                    didOpen: (q) => {
                        console.log('otwarty swal');
                        $('#newMember').select2({
                        });
                        $('#newMemberSubgroups').select2({
                            placeholder: "Nie wybrano"
                        });

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


function addMemberToGroup(){
    var group = "{{ $group->id ?? ''}}";
    var newMember = $('#newMember').val();
    var newMemberSubgroups = $('#newMemberSubgroups').val();

    console.log(newMember);
    console.log(newMemberSubgroups);

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
                subgroups: newMemberSubgroups
            },
            success: function(data) {
                console.log('dodano');
                $('#tabela').DataTable().ajax.reload();
            },
            error: function(data) {
                console.log('nie dodano');
            }
        })

}



});
    </script>
@endsection
