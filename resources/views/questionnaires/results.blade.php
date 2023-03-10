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
                                        class="btn btn-outline-primary"><i class="fa-solid fa-chevron-left me-sm-2"></i><span class="d-none d-sm-inline">Powrót<span></button></a>
                                </div>
                            </div>

                            <div class="mt-2 profile">
                                <div class="card-title">
                                    {{ $questionnaire->description }}

                                </div>

                               @if($questionnaire->type == 'closed')
                                @include('questionnaires.resultsClosedQuestionnaire')
                               @elseif($questionnaire->type == 'open')
                               @include('questionnaires.resultsOpenQuestionnaire')
                               @endif






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
