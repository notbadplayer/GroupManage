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
                                    Odtwarzacz
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ route('songs.index') }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Powrót</button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">

@php
    dump(public_path($song->file->location));

@endphp





                            </div>

                        </div>
                    </div>
                </div>
            </div>



        </section>

    </main>

    <script type="module">

var Player = new MidiPlayer.Player(function(event) {



	console.log(event);

});

Player.loadDataUri("http://chor.test/files/song/toto-africa.mid");
    Player.Play();



console.log(Player);

    </script>


    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif

@endsection
