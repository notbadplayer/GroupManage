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
                        <div class="card-body" id="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight card-title">
                                    Odtwarzacz
                                </div>
                                <div class="p-2 bd-highlight">
                                    <a href="{{ url()->previous() }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-chevron-left me-sm-2"></i><span
                                                class="d-none d-sm-inline">Powrót<span></button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">

                                <h5 class="card-title ms-2">{{ $song->name }}</h5>


                                <div class="row mb-1 justify-content-center">
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-4 text-start">
                                                <span id="midiCurrentTime"></span>
                                            </div>
                                            <div class="col-4">
                                            </div>
                                            <div class="col-4 text-end">
                                                <span id="midiDuration"></span>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="row mb-5 justify-content-center">
                                    <div class="col-sm-10">
                                        <div><input type="range" class="form-range" min="0" max="100"
                                                step="1" id="playBarRange" value=0></div>
                                    </div>
                                </div>

                                <div id='loadingMidi' class="row mb-5 justify-content-center">
                                    <div class="spinner-border text-primary" role="status"
                                        style="width: 50px; height: 50px;"><span
                                            class="visually-hidden">Wczytywanie...</span></div>
                                </div>


                                <div class="row mb-5 justify-content-center visually-hidden" id="midiControlButtons">
                                    <div class="col-sm-10 text-center">
                                        <button type="button" id="midibuttonPrev" class="btn btn-primary btn-lg me-1"><i
                                                class="fa-solid fa-backward"></i></button>
                                        <button type="button" id="midibuttonPlay" class="btn btn-primary btn-lg me-1"><i
                                                class="fa-solid fa-play"></i></button>
                                        <button type="button" id="midibuttonStop" class="btn btn-primary btn-lg me-1"><i
                                                class="fa-solid fa-stop"></i></button>
                                        <button type="button" id="midibuttonNext" class="btn btn-primary btn-lg me-1"><i
                                                class="fa-solid fa-forward"></i></button>
                                    </div>
                                </div>

                                <div class="row mb-5 justify-content-center visually-hidden" id='midiTempoBar'>
                                    <div class="col-8 col-sm-6 col-md-4 col-xl-3 text-center">
                                        Tempo: <span id="tempo-display"></span> bpm<br />
                                        <input type="range" id="midiTempoRange" class="form-range" min="50"
                                            max="150">
                                    </div>
                                </div>

                                <div id='trackList' class='row justify-content-center'>
                                </div>

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
        Soundfont.instrument(ac, soundfontDir+'{{$song->instrument}}').then(function (instrument) {

var $playingNotes = [];

	loadDataUri = function(dataUri) {
		Player = new MidiPlayer.Player(function(event) {
			if (event.name == 'Note on' && event.velocity > 0) {
				var note = instrument.play(event.noteName, ac.currentTime, {gain:(2*(event.velocity/100))});

                $playingNotes.push({'noteId': note.id, 'noteName': event.noteName+'_'+event.channel, 'channel':event.channel, });

			} else if(event.name == 'Note off' || event.velocity == 0){
                const found = $playingNotes.find(element => element.noteName == event.noteName+'_'+event.channel);
                if(found){
                    instrument.stop(0, [found.noteId])
                    $playingNotes = $playingNotes.filter(function(item) {
                    return item.noteId !== found.noteId
                    })
                }
            }
            $( "#playBarRange" ).val(100 - Player.getSongPercentRemaining());
            checkSongTime();
		});

		Player.loadDataUri(dataUri);
        $( "#loadingMidi" ).hide();
        $( "#midiControlButtons" ).removeClass("visually-hidden");
        $( "#midiTempoBar" ).removeClass("visually-hidden");

        Player.on('endOfFile', function() {
                clearPlayingNotes()
                Player.stop();
                $('#midibuttonPlay').html('<i class="fa-solid fa-play">')
                $( "#playBarRange" ).val(0);
                $( "#midiDuration" ).text(formatSeconds(Player.getSongTime()));
                $( "#midiCurrentTime" ).text('00:00');
            });
	}

    const midiFile = '{{ $midi }}';

	loadDataUri('data:audio/midi;base64,' + midiFile);

    for(let i=1; i<=(Player.tracks).length; i++){

        $( "#trackList" ).append( "<button type='button' class='btn btn-primary trackEnabler col-4 col-sm-5 col-md-2 col-lg-2 col-xl-1 me-4 mb-3' value='1' data-track="+i+">Głos "+i+"</button>" );
    }


        $( ".trackEnabler" ).on('click', function() {
        let $track = $( this ).text();
            if($( this ).val() == '1'){
                clearNotesOnChannel($( this ).data('track'));
                Player.disableTrack($( this ).data('track'));
                $( this ).val('0');
                $( this ).removeClass("btn-primary");
                $( this ).addClass("btn-secondary");
            } else {
                Player.enableTrack($( this ).data('track'));
                $( this ).val('1');
                $( this ).removeClass("btn-secondary");
                $( this ).addClass("btn-primary");
            }
        });


$('#midibuttonPlay').on( 'click', function () {
    Player.isPlaying() ?  (clearPlayingNotes(), Player.pause()) : checkTracks();
    $( "#playBarRange" ).val(100 - Player.getSongPercentRemaining());
    checkPlayButton();
});

$('#midibuttonStop').on( 'click', function () {
    clearPlayingNotes()
	Player.stop();
    $('#midibuttonPlay').html('<i class="fa-solid fa-play">')
    $( "#playBarRange" ).val(0);
    $( "#midiCurrentTime" ).text('00:00');
    $( "#midiDuration" ).text(formatSeconds(Player.getSongTime()));
});

$('#midibuttonPrev').on( 'click', function () {
    clearPlayingNotes()
    let $remaining = Player.getSongTimeRemaining();
    let $total = Player.getSongTime();
    let $wasPlaying =   Player.isPlaying() ? true : false;


    let $current = $total - $remaining;
    Player.skipToSeconds($current - 5);
    $( "#playBarRange" ).val(100 - Player.getSongPercentRemaining());
    if($wasPlaying) checkTracks();
    checkPlayButton();
    checkSongTime();
});

$('#midibuttonNext').on( 'click', function () {
    clearPlayingNotes()
    let $remaining = Player.getSongTimeRemaining();
    let $total = Player.getSongTime();
    let $wasPlaying =   Player.isPlaying() ? true : false;

    let $current = $total - $remaining;
    Player.skipToSeconds($current + 5);
    $( "#playBarRange" ).val(100 - Player.getSongPercentRemaining());
    if($wasPlaying) checkTracks();
    checkPlayButton();
    checkSongTime();
});


function checkPlayButton()
{
    if(Player.isPlaying()){
        $('#midibuttonPlay').html('<i class="fa-solid fa-pause"></i>')
    }else {
        $('#midibuttonPlay').html('<i class="fa-solid fa-play">')
    }
}

$( "#playBarRange" ).on('input change', function () {
    clearPlayingNotes()
    let $wasPlaying =   Player.isPlaying() ? true : false;
    Player.pause();
    Player.skipToPercent($( this ).val());
    if($wasPlaying){
        //Player.play();
        checkTracks(); //wrzucamy tą funkcję żeby zsynchronizować aktualnie włączone głosy
    }
    checkSongTime();
    checkPlayButton();
});



$( "#midiTempoRange" ).on( 'change', function () {
    Player.pause();
    Player.setTempo($( this ).val());
    checkTracks()
    $( "#tempo-display" ).text($( this ).val());
});

function formatSeconds($time)
{
    return new Date($time * 1000).toISOString().slice(14, 19);
}

function checkSongTime()
{
    $( "#midiCurrentTime" ).text(formatSeconds(Player.getSongTime() - Player.getSongTimeRemaining()));
    $( "#midiDuration" ).text(formatSeconds(Player.getSongTimeRemaining()));
}

function checkTracks()
{
    Player.play()
    $( ".trackEnabler" ).each(function() {
        if($( this ).val() == '0'){
            Player.disableTrack($( this ).data('track'));
        }
    });

}

function clearPlayingNotes()
{
    $playingNotes = [];
    instrument.stop();
}

function clearNotesOnChannel(channel)
{
    const found = $playingNotes.find(element => element.channel == channel);
        if(found){
            instrument.stop(0, [found.noteId])
            $playingNotes = $playingNotes.filter(function(item) {
            return item.noteId !== found.noteId
            })
        }
}

});

    </script>



    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif
@endsection
