@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Grupy</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Grupy</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('groups.edit', $group->id) }}">{{ $group->name }}</a></li>
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
                                    @if (isset($subgroup))
                                        Edycja grupy podrzędnej
                                    @else
                                        Dodawanie grupy podrzędnej: {{ $group->name }}
                                    @endif
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('groups.edit', $group->id) }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Powrót</button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <form method="post"
                                    action="{{ isset($subgroup) ? route('subgroups.update', $subgroup->id) : route('subgroups.store') }}">
                                    @csrf
                                    @if (isset($subgroup))
                                        @method('PUT')
                                    @endif

                                    <div class="row mb-3 profile-edit">
                                        <label for="name" class="col-sm-2 col-form-label fw-bold">Nazwa:</label>
                                        <div class="col-sm-10"> <input type="text"
                                                class="form-control @error('name')is-invalid @enderror" name="name"
                                                id="name" value="{{ old('name', $subgroup->name ?? '') }}" required>
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3 profile-edit">
                                        <label for="members" class="col-sm-2 col-form-label fw-bold">Uczestnicy:</label>
                                        <div class="col-sm-10 "> <select
                                                class="form-select members @error('members')is-invalid @enderror" name="members[]" multiple="multiple"
                                                id="members" value="{{ old('members', $group->members ?? '') }}" style="width: 100%">
                                     @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if(isset($members) && (in_array($user->id, $members))) selected @endif>{{ $user->name }} {{$user->surname }}</option>
                                     @endforeach
                                        </select>

                                        </div>
                                    </div>

                                    <input type="hidden" name="groupId" value="{{ $group->id }}">

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

{{-- Select2 --}}
<script type="module">


    $('#members').select2({
        placeholder: "Brak uczestników"
    });






    </script>



@endsection



