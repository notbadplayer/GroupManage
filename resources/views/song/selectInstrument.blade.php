<select class="form-select" aria-label="select kategory" id="instrument" name="instrument">
    <option value="piano.js" {{ (old('instrument') ?? ($song->instrument ?? '')) == 'piano.js' ? 'selected' : '' }}>
        pianino</option>
    <option value="acoustic_guitar_nylon-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'acoustic_guitar_nylon-mp3.js' ? 'selected' : '' }}>
        gitara</option>
    <option value="violin-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'violin-mp3.js' ? 'selected' : '' }}>
        skrzypce</option>
    <option value="viola-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'viola-mp3.js' ? 'selected' : '' }}>
        altówka</option>
    <option value="cello-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'cello-mp3.js' ? 'selected' : '' }}>
        wiolonczela</option>
    <option value="clarinet-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'clarinet-mp3.js' ? 'selected' : '' }}>
        klarnet</option>
    <option value="flute-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'flute-mp3.js' ? 'selected' : '' }}>
        flet</option>
    <option value="oboe-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'oboe-mp3.js' ? 'selected' : '' }}>
        obój</option>
    <option value="trumpet-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'trumpet-mp3.js' ? 'selected' : '' }}>
        trąbka</option>
    <option value="trombone-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'trombone-mp3.js' ? 'selected' : '' }}>
        puzon</option>
    <option value="tuba-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'tuba-mp3.js' ? 'selected' : '' }}>
        tuba</option>
    <option value="tenor_sax-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'tenor_sax-mp3.js' ? 'selected' : '' }}>
        tenor sax</option>
    <option value="soprano_sax-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'soprano_sax-mp3.js' ? 'selected' : '' }}>
        sopran sax</option>
    <option value="alto_sax-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'alto_sax-mp3.js' ? 'selected' : '' }}>
        alt sax</option>
    <option value="choir_aahs-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'choir_aahs-mp3.js' ? 'selected' : '' }}>
        chór aah</option>
    <option value="voice_oohs-mp3.js"
        {{ (old('instrument') ?? ($song->instrument ?? '')) == 'voice_oohs-mp3.js' ? 'selected' : '' }}>
        głos ooh</option>
</select>
