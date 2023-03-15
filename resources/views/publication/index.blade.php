@extends('layout.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Ogłoszenia</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Strona Główna</a></li>
                    <li class="breadcrumb-item active">Ogłoszenia</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mw-90">
                        <div class="card-body">
                            <h5 class="card-title">Lista Ogłoszeń</h5>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation"> <button class="nav-link active" id="active-tab"
                                        data-bs-toggle="tab" data-bs-target="#tabActive" type="button" role="tab"
                                        aria-controls="tabActive" aria-selected="true">Aktywne</button></li>
                                <li class="nav-item" role="presentation"> <button class="nav-link" id="profile-tab"
                                        data-bs-toggle="tab" data-bs-target="#tabArchive" type="button" role="tab"
                                        aria-controls="tabArchive" aria-selected="false">Archiwalne</button></li>

                                <li class="nav-item me-1 ms-auto" role="presentation">
                                    <a href="{{ route('publications.create') }}"><button type="button"
                                            class="btn btn-outline-primary"><i
                                                class="fa-solid fa-plus me-1"></i>Dodaj</button></a>
                                </li>

                            </ul>
                            <div class="tab-content pt-2" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabActive" role="tabpanel"
                                    aria-labelledby="active-tab">
                                    @include('publication.activePublications')
                                </div>
                                <div class="tab-pane fade" id="tabArchive" role="tabpanel" aria-labelledby="archive-tab">
                                    @include('publication.archivedPubications')
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </section>

        <script type="module">
             generateActiveData();
            var tabArchive = document.querySelector('button[data-bs-target="#tabArchive"]')
            tabArchive.addEventListener('show.bs.tab', function (event) {
                generateArchivedData();
            })

            var tabActive = document.querySelector('button[data-bs-target="#tabActive"]')
            tabActive.addEventListener('show.bs.tab', function (event) {
                generateActiveData();
            })


            $('#sidebar-mainpage-nav').addClass('collapsed');
            $('#sidebar-publications-nav').removeClass('collapsed');
            $('#publications-nav').addClass('show');
            $('#publications-nav-list').addClass('active');

        </script>
    </main>
    {{-- Wyświetlenie paska statusu --}}
    @if (Session::has('success'))
        @include('other.statusSuccess')
    @endif
@endsection
