@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Nuty</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active">Nuty</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mw-90">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight card-title">Lista nut</div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('notes.create') }}"><button type="button"
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
                                            <th scope="col">Kategoria</th>
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

                    ajax: "{{ route('notes.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'name', name: 'name', orderable: true,},
                        {data: 'category', name: 'category'},
                        {data: 'visibility', name: 'visibility'},
                    ],
                });

            });

            //kliknięcie w wiersz
            $(document).ready(function () {

                var table = $('#tabela').DataTable();

                $('#tabela tbody').on( 'click', 'tr', function () {
                    var data = table.row( this ).data()
                     window.location.href = "/notes/edit/"+data.id;
                });



            });


        </script>
    </main>
    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif
@endsection
