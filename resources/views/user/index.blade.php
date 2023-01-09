@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Użytkownicy</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active">Lista użytkowników</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mw-90">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight card-title">Użytkownicy</div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('users.create') }}"><button type="button" class="btn btn-outline-primary"><i class="fa-solid fa-plus me-1"></i>Dodaj</button></a>
                                </div>
                              </div>

                            <div class="mt-2 ">
                                <table class="table tabela" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Surname</th>
                                            <th scope="col">email</th>
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
                 $(function () {

                    var table = $('.tabela').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: "{{ route('users.data') }}",
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'name', name: 'name', orderable: true,},
                            {data: 'surname', name: 'surname'},
                            {data: 'email', name: 'email'},
                        ]
                    });

                    });

                </script>





    </main>
@endsection
