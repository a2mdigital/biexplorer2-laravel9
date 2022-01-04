<nav class="navbar" id="navbar">
  <a href="#" class="sidebar-toggler">
    <i data-feather="menu"></i>
  </a>
  <div id="navbar-superior-admin" class="navbar-content" style="align-items: center;">
    <p class="{{Auth::guard('web')->user()->menu_color ? 'titulo-dark' : 'titulo-light'}}"> 
      @yield('titulo-pagina')
    </p>  

    <ul class="navbar-nav">
   
      <li class="nav-item dropdown nav-profile">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i data-feather="list"></i>
        </a>
        <div class="dropdown-menu" id="dropdown-menu-admin" aria-labelledby="profileDropdown">
          <div class="dropdown-header d-flex flex-column align-items-center">
            <div class="figure mb-3">
             <!-- <img src="{{ url('https://via.placeholder.com/80x80') }}" alt=""> -->
            </div>
            <div class="info text-center">
              <p class="name font-weight-bold mb-0">{{Auth::guard('web')->user()->name}}</p>
              <p class="email text-muted mb-3">{{Auth::guard('web')->user()->email}}</p>
            </div>
          </div>
          <div class="dropdown-body">
            <ul class="profile-nav p-0 pt-3">
              <li class="nav-item">
                <a href="{{route('tenant.usuario.trocar.senha')}}" class="nav-link">
                  <i data-feather="edit"></i>
                  <span>{{__('auth.change_password')}}</span>
                </a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                      <i data-feather="log-out"></i>
                                      <span>{{__('auth.logout_user')}}</span>
                </a>
               <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    @csrf
               </form>
              </li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</nav>