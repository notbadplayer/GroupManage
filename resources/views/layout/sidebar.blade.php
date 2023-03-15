<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item"> <a class="nav-link" id="sidebar-mainpage-nav" href="{{ route('home') }}"> <i class="bi bi-grid"></i> <span>Strona
                    Główna</span> </a></li>
        @can('admin-level')
            <li class="nav-item">
                <a class="nav-link collapsed" id="sidebar-users-nav" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#"> <i
                        class="fa-regular fa-user"></i><span>Użytkownicy</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li> <a id="users-nav-list" href="{{ route('users.index') }}"> <i class="fa-regular fa-circle"></i><span>Lista użytkowników</span>
                        </a></li>
                    <li> <a id="users-nav-add" href="{{ route('users.create') }}"> <i class="fa-regular fa-circle"></i><span>Dodaj użytkownika</span>
                        </a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" id="sidebar-groups-nav" data-bs-target="#groups-nav" data-bs-toggle="collapse" href="#"> <i
                        class="fa-solid fa-user-group"></i><span>Grupy</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                <ul id="groups-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li> <a id="groups-nav-list" href="{{ route('groups.index') }}"> <i class="fa-regular fa-circle"></i><span>Lista grup</span> </a>
                    </li>
                    <li> <a id="groups-nav-add" href="{{ route('groups.create') }}"> <i class="fa-regular fa-circle"></i><span>Dodaj grupę</span> </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" id="sidebar-publications-nav" data-bs-target="#publications-nav" data-bs-toggle="collapse" href="#">
                    <i class="fa-solid fa-file-lines"></i><span>Ogłoszenia</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="publications-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li> <a id="publications-nav-list" href="{{ route('publications.index') }}"> <i class="fa-regular fa-circle"></i><span>Lista
                                Ogłoszeń</span> </a></li>
                    <li> <a id="publications-nav-add" href="{{ route('publications.create') }}"> <i class="fa-regular fa-circle"></i><span>Dodaj
                                Ogłoszenie</span> </a></li>
                </ul>
            </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link collapsed" id="sidebar-notes-nav" data-bs-target="#notes-nav" data-bs-toggle="@if(Gate::check('admin-level')){{'collapse'}}@endif" href="@if(!(Gate::check('admin-level'))) {{route('notes.index')}} @else # @endif"> <i
                    class="fa-solid fa-music"></i><span>Nuty</span><i class="bi bi-chevron-down ms-auto"></i> </a>
            <ul id="notes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li> <a id="notes-nav-list" href="{{ route('notes.index') }}"> <i class="fa-regular fa-circle"></i><span>Lista nut</span> </a></li>
                @can('admin-level')
                    <li> <a id="notes-nav-add" href="{{ route('notes.create') }}"> <i class="fa-regular fa-circle"></i><span>Dodaj nuty</span> </a>
                    </li>
                    <li> <a href="{{ route('categories.index') }}"> <i class="fa-regular fa-circle"></i><span>Kategorie</span> </a>
                    </li>
                @endcan
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" id="sidebar-songs-nav" data-bs-target="#player-nav" data-bs-toggle="@if(Gate::check('admin-level')){{'collapse'}}@endif" href="@if(!(Gate::check('admin-level'))) {{route('songs.index')}} @else # @endif"> <i
                    class="fa-solid fa-circle-play"></i><span>Odtwarzacz</span><i
                    class="bi bi-chevron-down ms-auto"></i> </a>
            <ul id="player-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li> <a id="songs-nav-list" href="{{ route('songs.index') }}"> <i class="fa-regular fa-circle"></i><span>Lista utworów</span> </a>
                </li>
                @can('admin-level')
                    <li> <a id="songs-nav-add" href="{{ route('songs.create') }}"> <i class="fa-regular fa-circle"></i><span>Dodaj utwór</span> </a>
                    </li>
                    <li> <a href="{{ route('categories.index') }}"> <i class="fa-regular fa-circle"></i><span>Kategorie</span> </a>
                    </li>
                @endcan
            </ul>
        </li>
        @can('admin-level')
            <li class="nav-item">
                <a class="nav-link collapsed" id="sidebar-events-nav" data-bs-target="#events-nav" data-bs-toggle="collapse" href="#"> <i
                        class="fa-solid fa-calendar-days"></i><span>Wydarzenia</span><i
                        class="bi bi-chevron-down ms-auto"></i> </a>
                <ul id="events-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li> <a id="events-nav-list" href="{{ route('events.index') }}"> <i class="fa-regular fa-circle"></i><span>Lista wydarzeń</span>
                        </a></li>
                    <li> <a id="events-nav-add" href="{{ route('events.create') }}"> <i class="fa-regular fa-circle"></i><span>Dodaj wydarzenie</span>
                        </a></li>
                </ul>
            </li>
        @endcan


        <li class="nav-item"> <a id="sidebar-profile-nav" class="nav-link collapsed" href="{{ route('users.profile')}}"> <i class="fa-regular fa-address-card"></i>
                <span>Konto</span> </a></li>

    </ul>
</aside>
