@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Ogłoszenia</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('publications.index') }}">Ogłoszenia</a></li>
                    <li class="breadcrumb-item active">Ankiety</li>
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
                                    Wyniki Ankiety
                                </div>

                                <div class="p-2 bd-highlight">
                                    <a href="{{ url()->previous() }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-rotate-left me-2"></i>Powrót</button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <div class="card-title">
                                    {{ $questionnaire->description }}

                                </div>

                                {{-- Uprawnieni do głosowania{{count($entitledToVote)}}
                                Oddane głosy: {{count($votes)}} --}}


                                <div style="width: 600px; margin: auto;">
                                    <canvas id="myChart"></canvas>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <script type="module">

const data = {
  labels: [
    'Red',
    'Blue',
    'Yellow'
  ],
  datasets: [{
    label: 'My First Dataset',
    data: [{{$yes}}, {{$no}},  {{$held}}],
    backgroundColor: [
      'rgb(255, 99, 132)',
      'rgb(54, 162, 235)',
      'rgb(0, 0, 0)',
    ],
    hoverOffset: 4
  }]
};

const config = {
  type: 'pie',
  data: data,
};



new Chart(
    document.getElementById('myChart'),
    config
);

                </script>




        </section>

    </main>

    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif



    <script type="module">


    </script>
@endsection
