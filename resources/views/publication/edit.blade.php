@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Publikacje</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('groups.index') }}">Publikacje</a></li>
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
                                    @if (isset($publication))
                                        Edycja Publikacji
                                    @else
                                        Dodawanie Publikacji
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

                                    <form class="row g-3">
                                        <div class="col-md-12 profile-edit mb-3">
                                            <label for="inputName5" class="form-label">Tytuł:</label> <input type="text" class="form-control" id="inputName5">
                                        </div>
                                        <div class="col-md-12 profile-edit">
                                            <label for="inputName5" class="form-label">Treść:</label>
                                            <textarea id="editor" class="block w-full mt-1 rounded-md" name="description" rows="3" cols="10"></textarea>
                                        </div>

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
                    processing: true,
                    serverSide: true,
                    responsive: true,
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


            ClassicEditor.create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );


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


});
    </script>
@endsection
