<div class="card info-card revenue-card">
    <div class="card-body">
        <h5 class="card-title">Ostatnio dodane nuty</h5>
        <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i
                    class="fa-brands fa-itunes-note"></i></div>
            <a href="/file-download/note/{{ $latestNote->id }}">
                <div class="ps-3">
                    <h6>{{ $latestNote->name }}</h6>
                    <span class="text-muted small pt-2 ps-1">Kliknij aby pobrać</span>
                </div>
            </a>
        </div>
    </div>
</div>
