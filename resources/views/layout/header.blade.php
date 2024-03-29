<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between"> <a href="{{ route('home') }}"
            class="logo d-flex align-items-center"> <img src="{{ asset('logo.png') }}" alt=""> <span
                class="d-none d-lg-block">JFstudio</span> </a> <i class="fa-solid fa-bars toggle-sidebar-btn"></i></div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}
                        {{ Auth::user()->surname }}</span> </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ Auth::user()->name }} {{ Auth::user()->surname }}</h6>
                        <ul>
                            @if (Gate::check('admin-level'))
                                <li>Administrator</li>
                            @else
                                @foreach (Auth::user()->groups->unique() as $group)
                                    @if (count($group->subgroups->whereIn('id', Auth::user()->subgroups->pluck('id'))) > 0)
                                        @foreach ($group->subgroups->whereIn('id', Auth::user()->subgroups->pluck('id')) as $subgroup)
                                            <li>{{ $group->name }}-{{ $subgroup->name }}</li>
                                        @endforeach
                                    @else
                                        <li>{{ $group->name }}</li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('users.profile') }}"> <i
                                class="fa-regular fa-user"></i> <span>Mój profil</span> </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Wyloguj się</span> </a></li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </ul>
            </li>
        </ul>
    </nav>
</header>
