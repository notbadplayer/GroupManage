@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Wydarzenia</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('notes.index') }}">Wydarzenia</a></li>
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
                                    @if (isset($event))
                                        Edycja Wydarzenia
                                    @else
                                        Dodawanie Wydarzenia
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ url()->previous() }}"><button type="button"
                                            class="btn btn-outline-primary"><i class="fa-solid fa-chevron-left me-sm-2"></i><span class="d-none d-sm-inline">Powrót<span></button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($event) ? route('events.update', $event->id) : route('events.store') }}"
                                    enctype='multipart/form-data'>
                                    @csrf
                                    @if (isset($event))
                                        @method('PUT')
                                    @endif

                                    <div class="col-md-12 profile-edit mb-3">
                                        <label for="name" class="form-label">Tytuł:</label> <input type="text"
                                            class="form-control @error('name')is-invalid @enderror" id="name"
                                            name="name" value="{{ old('name', $event->name ?? '') }}">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="row g-3 profile-edit mb-3">
                                        <div class="col-md-6"> <label for="date" class="form-label">Data:</label> <input
                                                type="date" name="date"
                                                class="form-control @error('date')is-invalid @enderror" id="date"
                                                value="{{ old('date', $event->date ?? '') }}">
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-6"> <label for="time" class="form-label">Godzina:</label>
                                            <input type="time" name="time"
                                                class="form-control @error('time')is-invalid @enderror" id="time"
                                                value="{{ old('time', $event->time ?? '') }}">
                                        </div>
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



                                    <div class="float-end mb-3 mt-3"> <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-check me-1"></i>Zapisz</button></div>

                                </form>

                                @if (isset($song))
                                    <a href="{{ route('songs.play', ['song' => $song->id]) }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Odtwarzaj</button></a>
                                @endif

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


        $('#sidebar-mainpage-nav').addClass('collapsed');
        $('#sidebar-events-nav').removeClass('collapsed');
        $('#events-nav').addClass('show');
        @if(!(isset($event)))
        $('#events-nav-add').addClass('active');
        @endif

    </script>
@endsection
