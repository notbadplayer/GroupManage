@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <script src="{{asset('cke/ckeditor.js')}}"></script>
        <div class="pagetitle">
            <h1>Ogłoszenia</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('groups.index') }}">Ogłoszenia</a></li>
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
                                    @if (isset($publication) && !$publication->archived)
                                        Edycja Ogłoszenia
                                    @elseif(isset($publication) && $publication->archived)
                                        Podgląd Ogłoszenia (archiwum)
                                    @else
                                        Dodawanie Ogłoszenia
                                    @endif
                                </div>

                                <div class="p-2 bd-highlight">
                                    <a href="{{ url()->previous() }}"><button type="button"
                                        class="btn btn-outline-primary"><i class="fa-solid fa-chevron-left me-sm-2"></i><span class="d-none d-sm-inline">Powrót<span></button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($publication) ? route('publications.update', $publication->id) : route('publications.store') }}">
                                    @csrf
                                    @if (isset($publication))
                                        @method('PUT')
                                    @endif
                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="name" class="form-label">Tytuł:</label> <input type="text"
                                            class="form-control @error('name')is-invalid @enderror" id="name"
                                            name="name" value="{{ old('name', $publication->name ?? '') }}"
                                            @if (isset($publication) && $publication->archived) disabled @endif autocomplete="off">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="name" class="form-label">Widoczne dla:</label>
                                        <select class="form-select members @error('members')is-invalid @enderror"
                                            name="visibility[]" multiple="multiple" id="visibility"
                                            value="{{ old('members', $group->members ?? '') }}" style="width: 100%"
                                            @if (isset($publication) && $publication->archived) disabled @endif>
                                            @foreach ($groups ?? [] as $group)
                                                <option value="group:{{ $group->id }}"
                                                    @if (isset($visibleGroups) && in_array($group->id, $visibleGroups)) selected @endif>{{ $group->name }}
                                                </option>
                                            @endforeach

                                            @foreach ($subgroups ?? [] as $subgroup)
                                                <option value="subgroup:{{ $subgroup->id }}"
                                                    @if (isset($visibleSubgroups) && in_array($subgroup->id, $visibleSubgroups)) selected @endif>{{ $subgroup->name }}
                                                </option>
                                            @endforeach

                                            @foreach ($users ?? [] as $user)
                                                <option value="user:{{ $user->id }}"
                                                    @if (isset($visibleUsers) && in_array($user->id, $visibleUsers)) selected @endif>{{ $user->name }}
                                                    {{ $user->surname }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 profile-edit">
                                        <label for="editor" class="form-label">Treść:</label>
                                        <textarea id="editor" class="block w-full mt-1 rounded-md @error('content')is-invalid @enderror" name="content">
                                            {{ old('content', $publication->content ?? '') }}
                                        </textarea>
                                        @if ($errors->has('content'))
                                            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>

                                    {{-- <div class="form-check form-switch mt-4 ms-2">

                                        <!-- Size of the default switch will increase 1.8 times -->
                                        <input class="form-check-input " type="checkbox" role="switch" id="allowComments"
                                            name="allowComments" style="transform: scale(1.7);"
                                            @if (isset($publication) && $publication->archived) disabled @endif>

                                        <label class="form-check-label ms-3" for="allowComments">Zezwalaj na
                                            komentowanie</label>
                                    </div> --}}

                                    <div class="form-check form-switch mt-4 ms-2">

                                        <!-- Size of the default switch will increase 1.8 times -->
                                        <input class="form-check-input " type="checkbox" role="switch" id="sendMail"
                                            name="sendMail" style="transform: scale(1.7);"
                                            @if (isset($publication) && $publication->archived) disabled @endif>

                                        <label class="form-check-label ms-3" for="allowComments">Wyślij e-mail</label>
                                    </div>


                                    <div class="card mt-4">
                                        <div class="accordion" id="publicationAccordion">
                                            @include('questionnaires.edit')

                                        </div>

                                    </div>




                                    <div class="float-end mb-3 mt-3">

                                        @if((isset($publication) && !($publication->archived)))
                                            <a class="btn btn-outline-danger me-3" id="buttonArchivePublication"><i
                                                    class="fa-solid fa-box-archive me-1"></i>Przenieś do archiwum</a>
                                        @endif

                                        <button type="submit" class="btn btn-primary"
                                            @if (isset($publication) && $publication->archived) disabled @endif><i
                                                class="fa-solid fa-check me-1"></i>Zapisz</button>

                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @isset($publication)
            <form method="post" action="{{route('publications.archive', ['Publication'=> $publication->id])}}" id="archivePublication">
                @csrf
            </form>
            @endisset

        </section>

    </main>

    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif


    {{-- Generowanie tabeli uczestników --}}
    <script type="module">

        @if ((isset($publication) && $publication->allowComments))
            $('#allowComments').click();
        @endif

        $('#visibility').select2({
                placeholder: "Wszyscy"
            });

        $(document).ready(function () {


        var $editor = ClassicEditor.create( document.querySelector( '#editor' ),{
            simpleUpload: {
            // The URL that the images are uploaded to.
            uploadUrl: "{{route('file.upload', ['assignedTo' => 'publication','_token' => csrf_token() ])}}",

            // Enable the XMLHttpRequest.withCredentials property.
            withCredentials: true,

            // Headers sent along with the XMLHttpRequest to the upload server.
            headers: {
                'X-CSRF-TOKEN': 'CSRF-Token',
                Authorization: 'Bearer <JSON Web Token>'
            },
        }
    }){!! (isset($publication) && $publication->archived) ? '.then(editor => {editor.enableReadOnlyMode("editor");})' : ''!!}
            .catch( error => {
                console.error( error );
         } );

        });


        //Kliknięciee przycisku "Przenieś do aarchiwum":
$('#buttonArchivePublication').on( 'click', function () {
    var html = 'Przenieść do archiwum? Wpisy z archiwum nie są wyświetlane na stronie głównej.';

Swal.fire({
                    title: 'Archiwizuj',
                    html: html,
                    icon: 'warning',
                    confirmButtonText: 'Tak, przenieś',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: 'true',
                    cancelButtonText: 'Anuluj',
                    }).then((result) =>
                        {
                            if(result.isConfirmed){
                                $('#archivePublication').submit();
                            }
                        }
                    );
});


$('#sidebar-mainpage-nav').addClass('collapsed');
$('#sidebar-publications-nav').removeClass('collapsed');
$('#publications-nav').addClass('show');
@if(!(isset($publication)))
$('#publications-nav-add').addClass('active');
@endif

    </script>
@endsection
