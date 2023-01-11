@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Grupy</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('groups.index') }}">Grupy</a></li>
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
                                    @if (isset($group))
                                        Edycja Grupę
                                    @else
                                        Dodawanie grupy
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('groups.index') }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Powrót</button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($group) ? route('groups.update', $group->id) : route('groups.store') }}">
                                    @csrf
                                    @if (isset($group))
                                        @method('PUT')
                                    @endif

                                    <div class="row mb-3 profile-edit">
                                        <label for="name" class="col-sm-2 col-form-label fw-bold">Nazwa:</label>
                                        <div class="col-sm-10"> <input type="text"
                                                class="form-control @error('name')is-invalid @enderror" name="name"
                                                id="name" value="{{ old('name', $group->name ?? '') }}">
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="description" class="col-sm-2 col-form-label fw-bold">Opis:</label>
                                        <div class="col-sm-10 "> <input type="text"
                                                class="form-control @error('description')is-invalid @enderror"
                                                name="description" id="description"
                                                value="{{ old('description', $group->description ?? '') }}">
                                            @if ($errors->has('description'))
                                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (isset($group))
                                        <div class="row mb-3 profile-edit">
                                            <label for="description" class="col-sm-2 col-form-label fw-bold">Grupy
                                                podległe:</label>
                                            <div class="col-sm-10 ">
                                                @foreach($group->subgroups as $subgroup)
                                                <a href="{{ route('subgroups.edit', $subgroup->id) }}"><button type="button" class="btn btn-success mb-2 me-2"> {{ $subgroup->name }} <span class="badge bg-white text-success ms-1">4</span></button></a>
                                                @endforeach
                                                <a href="{{ route('subgroups.create', $group->id) }}"><button type="button"
                                                        class="btn btn-outline-success mb-2 me-2"><i
                                                            class="fa-solid fa-people-group me-2"></i>Dodaj</button></a>

                                            </div>
                                        </div>
                                    @endif

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
@endsection
