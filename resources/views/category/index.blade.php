@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Kategorie</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active">Kategorie</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mw-90">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight card-title">Lista kategorii</div>
                                <div class="p-2 bd-highlight">
                                    <button type="button" id='addCategory' class="btn btn-outline-primary"><i
                                            class="fa-solid fa-plus me-1"></i>Dodaj</button>
                                </div>
                            </div>

                            <div class="mt-2 ">
                                <table class="table tabela table-hover" id="tabela" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nazwa</th>
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

                    ajax: "{{ route('categories.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'name', name: 'name', orderable: true,},


                    ],
                });

            });

            //kliknięcie w wiersz: edycja
            $(document).ready(function () {

                var table = $('#tabela').DataTable();


                $('#tabela tbody').on( 'click', 'tr', function () {
                    var data = table.row( this ).data()
                    Swal.fire({
                        title: 'Edytuj kategorię',
                        icon: 'question',
                        input: 'text',
                        inputValue: data.name,
                        inputLabel: 'Podaj nową nazwę kategorii:',
                        confirmButtonText: 'Zapisz',
                        confirmButtonColor: '#0d6efd',
                        showCancelButton: 'true',
                        cancelButtonText: 'Anuluj',
                        showDenyButton: true,
                        denyButtonText: 'Usuń',
                        }).then((result) =>
                            {
                                if(result.isConfirmed){
                                    addCategory(result.value, data.id);
                                }else if (result.isDenied) {
                                    removeCategoryConfirm(data.id, data.name);
                                }
                            }
                        );
                });

            });

            //Kliknięcie przycisku "dodaj kategorię":
            $('#addCategory').on( 'click', function () {
            Swal.fire({
                        title: 'Dodaj kategorię',
                        icon: 'question',
                        input: 'text',
                        inputLabel: 'Podaj nazwę kategorii:',
                        confirmButtonText: 'Dodaj',
                        confirmButtonColor: '#0d6efd',
                        showCancelButton: 'true',
                        cancelButtonText: 'Anuluj',
                        }).then((result) =>
                            {
                                if(result.isConfirmed){
                                    addCategory(result.value);
                                }
                            }
                        );
            });

            function addCategory($name, $id = false){
                let url = ($id ? "categories/update/"+$id : "{{ route('categories.store') }}");
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
                    url: url,
                    method: 'POST',
                    data: {
                        name: $name,
                    },
                    success: function(data) {
                        $('.tabela').DataTable().ajax.reload();

                        Toast.fire({
                            icon: 'success',
                            title:  ($id ? "Zapisano zmiany" : "Dodano kategorię")
                            })
                    },
                    error: function(data) {
                        Toast.fire({
                            icon: 'error',
                            title: ($id ? "Błąd edycji kategorii" : "Błąd dodawania kategorii")
                            })
                    }
                })

            }

            function removeCategoryConfirm($id, $name){

                Swal.fire({
                        title: 'Czy na pewno usunąć kategorię: '+$name+' ?',
                        icon: 'warning',
                        confirmButtonText: 'Usuń',
                        confirmButtonColor: '#d33',
                        showCancelButton: 'true',
                        cancelButtonText: 'Anuluj',
                        }).then((result) =>
                            {
                                if(result.isConfirmed){
                                    removeCategory($id);
                                }
                            }
                        );
             };

             function removeCategory($id) {

                let url ="categories/destroy/"+$id;

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
                    url: url,
                    method: 'POST',
                    success: function(data) {
                        $('.tabela').DataTable().ajax.reload();

                        Toast.fire({
                            icon: 'success',
                            title:  "Usunięto kategorię"
                            })
                    },
                    error: function(data) {
                        Toast.fire({
                            icon: 'error',
                            title: "Błąd usuwania kategorii"
                            })
                    }
                })

            }

        </script>
    </main>
@endsection
