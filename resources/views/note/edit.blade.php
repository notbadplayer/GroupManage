@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Nuty</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('notes.index') }}">Nuty</a></li>
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
                                    @if (isset($note))
                                        Edycja pliku z nutami
                                    @else
                                        Dodawanie pliku z nutami
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('notes.index') }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Powrót</button></a>
                                </div>
                            </div>

                            @php
                                dump($errors)
                            @endphp

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($note) ? route('notes.update', $note->id) : route('notes.store') }}" enctype='multipart/form-data'>
                                    @csrf
                                    @if (isset($note))
                                        @method('PUT')
                                    @endif

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="name" class="form-label">Tytuł:</label> <input type="text"
                                            class="form-control @error('name')is-invalid @enderror" id="name"
                                            name="name" value="{{ old('name', $note->name ?? '') }}">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="name" class="form-label">Widoczne dla:</label>
                                        <select class="form-select members @error('members')is-invalid @enderror"
                                            name="visibility[]" multiple="multiple" id="visibility"
                                            value="{{ old('members', $group->members ?? '') }}" style="width: 100%">
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

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="category" class="form-label">Kategoria:</label>
                                        <select class="form-select" aria-label="select kategory" name="category">
                                            <option selected value='0'>Wybierz kategorię:</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                         </select>
                                    </div>

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="file" class="form-label">Plik:</label> <input class="form-control @error('file')is-invalid @enderror" type="file" id="file" name="file">
                                        @if ($errors->has('file'))
                                            <div class="invalid-feedback">{{ $errors->first('file') }}</div>
                                        @endif
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


        $('#visibility').select2({
                placeholder: "Wszyscy"
            });


        $(document).ready(function () {


        ClassicEditor.create( document.querySelector( '#editor' ),{
            simpleUpload: {
            // The URL that the images are uploaded to.
            uploadUrl: "{{route('file.upload', ['location' => 'publication','_token' => csrf_token() ])}}",

            // Enable the XMLHttpRequest.withCredentials property.
            withCredentials: true,

            // Headers sent along with the XMLHttpRequest to the upload server.
            headers: {
                'X-CSRF-TOKEN': 'CSRF-Token',
                Authorization: 'Bearer <JSON Web Token>'
            }
        }


    } )
            .catch( error => {
                console.error( error );
         } );

        });

    </script>
@endsection
