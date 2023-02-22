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

                                <h5 class="card-title ms-2">{{ $song->name }}</h5>


                                <div id='trackList'>

                                </div>
                                <button type="button" id="midibuttonPrev" class="btn btn-primary btn-lg"><i class="fa-solid fa-backward-step"></i></button>
                                <button type="button" id="midibuttonPlay" class="btn btn-primary btn-lg"><i class="fa-solid fa-play"></i></button>
                                <button type="button" id="midibuttonStop" class="btn btn-primary btn-lg"><i class="fa-solid fa-stop"></i></button>
                                <button type="button" id="midibuttonNext" class="btn btn-primary btn-lg"><i class="fa-solid fa-forward-step"></i></button>


                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </section>


    </main>


    <script type="module">
        var Player =  new MidiPlayer.Player();
        var loadFile, loadDataUri, Player;
        var AudioContext = window.AudioContext || window.webkitAudioContext || false;
        var ac = new AudioContext || new webkitAudioContext;


        var soundfontDir = "{{ addcslashes($soundFontDir, "\\") }}";
        Soundfont.instrument(ac, soundfontDir+'piano.js' ).then(function (instrument) {




	loadDataUri = function(dataUri) {
		Player = new MidiPlayer.Player(function(event) {
			if (event.name == 'Note on' && event.velocity > 0) {
				instrument.play(event.noteName, ac.currentTime, {gain:event.velocity/100});
				//document.querySelector('#track-' + event.track + ' code').innerHTML = JSON.stringify(event);
				//console.log(event);
			}


		});

		Player.loadDataUri(dataUri);
        //Player.play();

		//buildTracksHtml();
		//play();


	}

    const midiFile = '{{ $midi }}';

	loadDataUri('data:audio/midi;base64,' + midiFile);


    for(let i=1; i<=(Player.tracks).length; i++){
        $( "#trackList" ).append( "<button class='trackEnabler' value='1'>"+i+"</button>" );
    }
        $( ".trackEnabler" ).on( "click", function() {
        let $track = $( this ).text();
        console.log($track);
            if($( this ).val() == '1'){
                console.log('jest wlaczone to wylaczam')
                Player.disableTrack($track);
                $( this ).attr('value', '0');

            } else {
                console.log('Wyłączone - odpalam')
                Player.enableTrack($track);
                $( this ).attr('value', '1');
            }


        });

});





$('#midibuttonPlay').on( 'click', function () {
    //Player.isPlaying() ? Player.pause() : Player.play();

    if(Player.isPlaying()){
        Player.pause()
        $('#midibuttonPlay').html('<i class="fa-solid fa-play">')
    }else {
        Player.play()
        $('#midibuttonPlay').html('<i class="fa-solid fa-pause"></i>')
        console.log(Player.getSongTimeRemaining());
    }
});

$('#midibuttonStop').on( 'click', function () {
	Player.stop();
    $('#midibuttonPlay').html('<i class="fa-solid fa-play">')
});

$('#midibuttonPrev').on( 'click', function () {
    let move = (Player.getSongTimeRemaining()-5;
    Player.skipToTick(tict);
    console.log(Player.getCurrentTick());
});

$('#midibuttonNext').on( 'click', function () {
    console.log(Player.getCurrentTick());
	let tict = (Player.getCurrentTick())+1;
    Player.skipToTick(tict);
        console.log(Player.getCurrentTick());
});








    </script>



    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif
@endsection
