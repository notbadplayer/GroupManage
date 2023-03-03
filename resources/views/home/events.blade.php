<div class="card">
    <div class="card-body">
        <h5 class="card-title">Nadchodzące wydarzenia</h5>
        <div class="activity">
            @foreach ($events as $event)
                <div class="activity-item d-flex">
                    <div class="activite-label text-center">
                        @if ($event->date == $today)
                        Dzisiaj<span class="invisible">XXXX</span>
                        @elseif($event->date == $tomorrow)
                        Jutro<span class="invisible">XXXXX</span>
                        @else
                        {{ date('d-m-Y', strtotime($event->date)) }}
                        @endif
                        <span class="me-2"> </span>
                    </div>
                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                    <div class="activity-content fw-bold text-dark">
                        {{ $event->name }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
