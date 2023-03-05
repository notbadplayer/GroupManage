@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle mb-4">
            <h1>Strona Główna</h1>
        </div>
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @if($noGroups)
                            @include('home.noGroups')
                        @endif
                        @foreach ($publications as $publication)
                            @include('home.publication')
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                   @include('home.events')
                   @include('home.recentAddedNotes')
                </div>
            </div>
        </section>
    </main>

@include('home.questionnaireJS')


@endsection
