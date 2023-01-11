@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Użytkownicy</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Użytkownicy</a></li>
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
                                    @if (isset($user))
                                        Edycja Użytkownika
                                    @else
                                        Dodawanie użytkownika
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('users.index') }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Powrót</button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
                                    @csrf
                                    @if (isset($user))
                                        @method('PUT')
                                    @endif

                                    <div class="row mb-3 profile-edit">
                                        <label for="name" class="col-sm-2 col-form-label fw-bold">Imię:</label>
                                        <div class="col-sm-10"> <input type="text"
                                                class="form-control @error('name')is-invalid @enderror" name="name"
                                                id="name" value="{{ old('name', $user->name ?? '') }}">
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="surname" class="col-sm-2 col-form-label fw-bold">Nazwisko:</label>
                                        <div class="col-sm-10 "> <input type="text"
                                                class="form-control @error('surname')is-invalid @enderror" name="surname"
                                                id="surname" value="{{ old('surname', $user->surname ?? '') }}">
                                            @if ($errors->has('surname'))
                                                <div class="invalid-feedback">{{ $errors->first('surname') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="email" class="col-sm-2 col-form-label fw-bold">Email:</label>
                                        <div class="col-sm-10"> <input type="email"
                                                class="form-control @error('email')is-invalid @enderror" name="email"
                                                id="email" value="{{ old('email', $user->email ?? '') }}">
                                            @if ($errors->has('email'))
                                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="phone" class="col-sm-2 col-form-label fw-bold">Telefon:</label>
                                        <div class="col-sm-10"> <input type="text"
                                                class="form-control @error('phone')is-invalid @enderror" name="phone"
                                                id="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                            @if ($errors->has('phone'))
                                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="phone" class="col-sm-2 col-form-label fw-bold">Członek grupy:</label>
                                        <div class="col-sm-10">
                                            @if (isset($user))
                                                @foreach ($user->subgroups as $subgroup)
                                                    <a href="{{ route('subgroups.edit', $subgroup->id) }}"><button
                                                            type="button" class="btn btn-success mb-2 me-2">
                                                            {{ $subgroup->group->name }} <span
                                                                class="badge bg-white text-success ms-1">{{ $subgroup->name }}</span></button></a>
                                                @endforeach
                                            @endif
                                            <a href=""><button type="button"
                                                class="btn btn-outline-success mb-2 me-2"><i
                                                    class="fa-solid fa-people-group me-2"></i>Dodaj do grupy</button></a>
                                        </div>
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
@endsection
