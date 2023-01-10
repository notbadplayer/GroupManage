@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Grupy</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active">Lista grup</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mw-90">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight card-title">Grupy</div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('groups.create') }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-plus me-1"></i>Dodaj</button></a>
                                </div>
                            </div>

                            <div class="mt-2 ">
                                <table class="table tabela table-hover" id="tabela" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nazwa</th>
                                            <th scope="col">Opis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <script type="module">
            //generowanie tabeli
            $(function () {
                var table = $('.tabela').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    responsive: {
            details: false
                    },

                    ajax: "{{ route('groups.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'name', name: 'name', orderable: true,},
                        {data: 'description', name: 'description'},
                    ],
                });

            });

            //kliknięcie w wiersz
            $(document).ready(function () {

                var table = $('#tabela').DataTable();

                $('#tabela tbody').on( 'click', 'tr', function () {
                    //console.log( table.row( this ).data().id );
                    var data = table.row( this ).data()

                    var htmlText = "";
                    htmlText = htmlText + '<div class="container mb-3"><div class="row"><div class="col-1 col-md-2"><i class="fa-solid fa-phone"></i></div><div class="col-11 col-md-8">'+data.phone+'</div></div></div>'
                    htmlText = htmlText + '<div class="container mb-3"><div class="row"><div class="col-1 col-md-2"><i class="fa-solid fa-at"></i></div><div class="col-11 col-md-8">'+data.email+'</div></div></div>'

                    Swal.fire({
                        title: data.name,
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
                                    window.location.href = "/groups/edit/"+data.id;
                                }
                            }
                        );

                } );

            });
        </script>
    </main>
    {{-- Wyświetlenie paska statusu - pomyślna aktualizacja/ dodanie użytkownika --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif
@endsection
