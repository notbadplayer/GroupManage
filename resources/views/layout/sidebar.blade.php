<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
       <li class="nav-item"> <a class="nav-link " href="{{route('home') }}"> <i class="bi bi-grid"></i> <span>Strona Główna</span> </a></li>
       <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#"> <i class="fa-regular fa-user"></i><span>Użytkownicy</span><i class="bi bi-chevron-down ms-auto"></i> </a>
          <ul id="users-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
             <li> <a href="{{ route('users.index') }}"> <i class="bi bi-circle"></i><span>Lista użytkowników</span> </a></li>
             <li> <a href="{{ route('users.create') }}"> <i class="bi bi-circle"></i><span>Dodaj użytkownika</span> </a></li>
          </ul>
       </li>
       <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#groups-nav" data-bs-toggle="collapse" href="#"> <i class="fa-solid fa-user-group"></i><span>Grupy</span><i class="bi bi-chevron-down ms-auto"></i> </a>
          <ul id="groups-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
             <li> <a href="{{ route('groups.index') }}"> <i class="bi bi-circle"></i><span>Lista grup</span> </a></li>
             <li> <a href="{{ route('groups.create') }}"> <i class="bi bi-circle"></i><span>Dodaj grupę</span> </a></li>
          </ul>
       </li>
       <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#publications-nav" data-bs-toggle="collapse" href="#"> <i class="fa-solid fa-file-lines"></i><span>Publikacje</span><i class="bi bi-chevron-down ms-auto"></i> </a>
        <ul id="publications-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
           <li> <a href="{{ route('publications.index') }}"> <i class="bi bi-circle"></i><span>Lista publikacji</span> </a></li>
           <li> <a href="{{ route('publications.create') }}"> <i class="bi bi-circle"></i><span>Dodaj publikację</span> </a></li>
        </ul>
     </li>
       <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#notes-nav" data-bs-toggle="collapse" href="#"> <i class="fa-solid fa-music"></i><span>Nuty</span><i class="bi bi-chevron-down ms-auto"></i> </a>
          <ul id="notes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
             <li> <a href="{{ route('notes.index') }}"> <i class="bi bi-circle"></i><span>Lista nut</span> </a></li>
             <li> <a href="{{ route('notes.create') }}"> <i class="bi bi-circle"></i><span>Dodaj nuty</span> </a></li>
             <li> <a href="{{ route('categories.index') }}"> <i class="bi bi-circle"></i><span>Kategorie</span> </a></li>
          </ul>
       </li>
       <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#"> <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i> </a>
          <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
             <li> <a href="charts-chartjs.html"> <i class="bi bi-circle"></i><span>Chart.js</span> </a></li>
             <li> <a href="charts-apexcharts.html"> <i class="bi bi-circle"></i><span>ApexCharts</span> </a></li>
             <li> <a href="charts-echarts.html"> <i class="bi bi-circle"></i><span>ECharts</span> </a></li>
          </ul>
       </li>
       <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#"> <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i> </a>
          <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
             <li> <a href="icons-bootstrap.html"> <i class="bi bi-circle"></i><span>Bootstrap Icons</span> </a></li>
             <li> <a href="icons-remix.html"> <i class="bi bi-circle"></i><span>Remix Icons</span> </a></li>
             <li> <a href="icons-boxicons.html"> <i class="bi bi-circle"></i><span>Boxicons</span> </a></li>
          </ul>
       </li>
       <li class="nav-heading">Pages</li>
       <li class="nav-item"> <a class="nav-link collapsed" href="users-profile.html"> <i class="bi bi-person"></i> <span>Profile</span> </a></li>
       <li class="nav-item"> <a class="nav-link collapsed" href="pages-faq.html"> <i class="bi bi-question-circle"></i> <span>F.A.Q</span> </a></li>
       <li class="nav-item"> <a class="nav-link collapsed" href="pages-contact.html"> <i class="bi bi-envelope"></i> <span>Contact</span> </a></li>
       <li class="nav-item"> <a class="nav-link collapsed" href="pages-register.html"> <i class="bi bi-card-list"></i> <span>Register</span> </a></li>
       <li class="nav-item"> <a class="nav-link collapsed" href="pages-login.html"> <i class="bi bi-box-arrow-in-right"></i> <span>Login</span> </a></li>
       <li class="nav-item"> <a class="nav-link collapsed" href="pages-error-404.html"> <i class="bi bi-dash-circle"></i> <span>Error 404</span> </a></li>
       <li class="nav-item"> <a class="nav-link collapsed" href="pages-blank.html"> <i class="bi bi-file-earmark"></i> <span>Blank</span> </a></li>
    </ul>
 </aside>
