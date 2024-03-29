@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Wydarzenia</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active">Wydarzenia</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mw-90">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight card-title">Lista wydarzeń</div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('events.create') }}"><button type="button"
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
                                            <th scope="col">Data</th>
                                            <th scope="col">Przypisanie</th>
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
                    language: {
                        url: "{{asset('pl.json')}}",
                    },
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    responsive: {
            details: false
                    },

                    ajax: "{{ route('events.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'name', name: 'name', orderable: true,},
                        {data: 'date', name: 'date'},
                        {data: 'visibility', name: 'visibility'},
                    ],
                });

            });

            //kliknięcie w wiersz
            $(document).ready(function () {

                var table = $('#tabela').DataTable();

                $('#tabela tbody').on( 'click', 'tr', function () {
                    var data = table.row( this ).data()
                     window.location.href = "/events/edit/"+data.id;
                });



            });

            $('#sidebar-mainpage-nav').addClass('collapsed');
            $('#sidebar-events-nav').removeClass('collapsed');
            $('#events-nav').addClass('show');
            $('#events-nav-list').addClass('active');


        </script>
    </main>
    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif
@endsection
