@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Utwory</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('songs.index') }}">Utwory</a></li>
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
                                    @if (isset($song))
                                        Edycja pliku z utworem
                                    @else
                                        Dodawanie pliku z utworem
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ url()->previous() }}"><button type="button"
                                        class="btn btn-outline-primary"><i class="fa-solid fa-chevron-left me-sm-2"></i><span class="d-none d-sm-inline">Powrót<span></button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($song) ? route('songs.update', $song->id) : route('songs.store') }}"
                                    enctype='multipart/form-data'>
                                    @csrf
                                    @if (isset($song))
                                        @method('PUT')
                                    @endif

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="name" class="form-label">Tytuł:</label> <input type="text"
                                            class="form-control @error('name')is-invalid @enderror" id="name"
                                            name="name" value="{{ old('name', $song->name ?? '') }}">
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
                                                <option value="{{ $category->id }}"
                                                    {{ (old('category') ?? ($song->category->id ?? '')) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="upload" class="form-label">Plik MIDI:</label> <input
                                            class="form-control @error('upload')is-invalid @enderror" type="file"
                                            id="file" name="upload">
                                        @if ($errors->has('upload'))
                                            <div class="invalid-feedback">{{ $errors->first('upload') }}</div>
                                        @endif
                                        @if (isset($song) && $song->file)
                                            <div class="activity-content mt-1"> Obecnie wybrany plik: <a
                                                    href="{{ $song->file->url }}"
                                                    class="fw-bold text-dark">{{ $song->file->name }}</a></div>
                                        @endif
                                    </div>

                                    <div class="float-end mb-3 mt-3">
                                        @if (isset($song))
                                            <a href="{{ route('songs.play', ['Song' => $song->id]) }}" class="btn btn-outline-primary me-1 me-md-3"><i class="fa-solid fa-play me-1"></i>Odtwórz</a>
                                        @endif

                                        @isset($song)
                                        <a class="btn btn-outline-danger me-1 me-md-3" id="buttonRemoveSong"><i class="fa-solid fa-trash me-1"></i></i>Usuń</a>
                                        @endisset

                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-check me-1"></i>Zapisz</button>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @isset($song)
            <form method="post" action="{{route('songs.destroy', ['Song'=> $song->id])}}" id="deleteSongForm">
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


        $('#visibility').select2({
                placeholder: "Wszyscy"
            });




//Kliknięciee przycisku "Usuń":
$('#buttonRemoveSong').on( 'click', function () {
    var html = 'Czy chcesz usunąć nuty?';

Swal.fire({
                    title: 'Usuń utwór',
                    html: html,
                    icon: 'warning',
                    confirmButtonText: 'Tak, usuń',
                    confirmButtonColor: '#dc3545',
                    showCancelButton: 'true',
                    cancelButtonText: 'Anuluj',
                    }).then((result) =>
                        {
                            if(result.isConfirmed){
                                $('#deleteSongForm').submit();
                            }
                        }
                    );
});


$('#sidebar-mainpage-nav').addClass('collapsed');
$('#sidebar-songs-nav').removeClass('collapsed');
$('#player-nav').addClass('show');
@if(!(isset($song)))
$('#songs-nav-add').addClass('active');
@endif

    </script>
@endsection
