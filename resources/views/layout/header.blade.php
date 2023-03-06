<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between"> <a href="{{ route('home') }}"
            class="logo d-flex align-items-center"> <img src="{{asset('logo.png')}}" alt=""> <span
                class="d-none d-lg-block">JFstudio</span> </a> <i class="bi bi-list toggle-sidebar-btn"></i></div>
    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#"> <input type="text"
                name="query" placeholder="Search" title="Enter search keyword"> <button type="submit"
                title="Search"><i class="bi bi-search"></i></button></form>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item d-block d-lg-none"> <a class="nav-link nav-icon search-bar-toggle " href="#"> <i
                        class="bi bi-search"></i> </a></li>
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <span
                        class="d-none d-md-block dropdown-toggle ps-2">{{Auth::user()->name}} {{Auth::user()->surname}}</span> </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{Auth::user()->name}} {{Auth::user()->surname}}</h6>
                        <ul>
                            @if(Gate::check('admin-level'))
                            <li>Administrator</li>
                            @else
                            @foreach(Auth::user()->groups as $group)
                                @if(count($group->subgroups->whereIn('id', Auth::user()->subgroups->pluck('id'))) > 0)
                                        @foreach($group->subgroups->whereIn('id', Auth::user()->subgroups->pluck('id')) as $subgroup)
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
                    <li> <a class="dropdown-item d-flex align-items-center" href="users-profile.html"> <i
                                class="bi bi-person"></i> <span>My Profile</span> </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="users-profile.html"> <i
                                class="bi bi-gear"></i> <span>Account Settings</span> </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="pages-faq.html"> <i
                                class="bi bi-question-circle"></i> <span>Need Help?</span> </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"> <i
                                class="bi bi-box-arrow-right"></i> <span>Wyloguj się</span> </a></li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                </ul>
            </li>
        </ul>
    </nav>
</header>
