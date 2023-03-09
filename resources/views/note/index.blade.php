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
                                    @can('admin-level')
                                        <a href="{{ route('notes.create') }}"><button type="button"
                                                class="btn btn-outline-primary"><i
                                                    class="fa-solid fa-plus me-1"></i>Dodaj</button></a>
                                    @endcan
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
                            <div class="text-end">
                                <button type="button" id="button-downloadAllNotes"
                                    class="btn btn-outline-primary mb-2 me-2 mt-5"><i
                                        class="fa-solid fa-download me-2"></i>Pobierz wszystkie nuty</button>
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
                        url: "{{ asset('pl.json') }}",
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

@if(Gate::allows('admin-level')) {
                        window.location.href = "/notes/edit/"+data.id;
                    } @else {
                        window.location.href = "/file-download/Note/"+data.id;
                    }
@endif


                });



            });


            $('#button-downloadAllNotes').on('click', function(){
                let htmlDownloadNotesText = '<select class="form-select" name="downloadNotes" id="downloadNotes" style="width: 100%"><option value="0">Wszystkie</option>@foreach($categories ?? [] as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></br>';

                Swal.fire({
                                title: 'Pobierz wszystkie nuty:',
                                html: htmlDownloadNotesText,
                                icon: 'question',
                                confirmButtonText: 'Pobierz',
                                confirmButtonColor: '#0d6efd',
                                showCancelButton: 'true',
                                cancelButtonText: 'Anuluj',
                                didOpen: (q) => {
                                    $('#downloadNotes').select2({
                                    });
                                    $('#downloadNotes').select2({
                                        placeholder: "Wszystkie"
                                    });

                                }

                                }).then((result) =>
                                    {
                                        //console.log(result)
                                        if(result.isConfirmed){
                                            downloadNotes()
                                        }
                                    }
                                );
            });

            function downloadNotes(){
                var $category = $('#downloadNotes').val();
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        }
                    });

                                    // Create a hidden iframe
                    var iframe = $('<iframe>', {
                        style: 'display:none',
                        src: '{{ route("file.downloadZip") }}?category=' + $category,
                    });

                    // Append the iframe to the body
                    $('body').append(iframe);
                                                }


        </script>
    </main>
    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif
@endsection
