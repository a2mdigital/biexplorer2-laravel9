<nav class="sidebar no-print">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      BI<span>EXPLORER</span>
    </a>
    <div class="sidebar-toggler {{Auth::guard('web')->user()->menu_contraido ? 'active' : 'not-active'}}">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Operações Relatório</li>
      <li class="nav-item {{ active_class(['/']) }}">
       @yield('link-voltar-relatorios')
      </li>
      <li class="nav-item {{ active_class(['/']) }}">
        <a href="#" onclick="atualizar()"  class="nav-link">
          <i class="link-icon" data-feather="refresh-ccw"></i>
          <span class="link-title">{{__('menu.update_report')}}</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['/']) }}">
        <a href="#" onclick="fullscreen()" class="nav-link">
          <i class="link-icon" data-feather="maximize-2"></i>
          <span class="link-title">FullScreen</span>
        </a>
      </li>
      <li class="nav-item nav-category">Main</li>
      
      <li class="nav-item {{ active_class(['/']) }}">
        <a href="{{ route('dashboard-admin')}}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      
      <li class="nav-item nav-category">{{__('menu.reports')}}</li>
      <li class="nav-item {{ active_class(['admin/tenant/grupos']) }}">
        <a href="{{ route('tenant.gruposrelatorio') }}" class="nav-link">
         <i class="link-icon" data-feather="slack"></i>
          <span class="link-title">{{__('menu.reports')}}</span>
        </a>
      </li>
      <li class="nav-item nav-category">{{__('menu.slideshow')}}</li>
      <li class="nav-item {{ active_class(['admin/tenant/playlists']) }}">
        <a href="{{ route('tenant.playlists') }}" class="nav-link">
         <i class="link-icon" data-feather="airplay"></i>
          <span class="link-title">{{__('menu.slideshow')}}</span>
        </a>
      </li>
      <li class="nav-item nav-category">{{__('menu.admin')}}</li>
      <li class="nav-item {{ active_class(['admin/tenant/usuarios']) }}">
        <a href="{{ route('tenant.usuarios') }}" class="nav-link">
         <i class="link-icon" data-feather="users"></i>
          <span class="link-title">{{__('menu.users')}}</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['admin/tenant/departamentos']) }}">
        <a href="{{ route('tenant.departamentos') }}" class="nav-link">
         <i class="link-icon" data-feather="briefcase"></i>
          <span class="link-title">{{__('menu.departaments')}}</span>
        </a>
      </li>
      <li class="nav-item nav-category">{{__('menu.customize')}}</li>
      <li class="nav-item">
      <div class="custom-control custom-switch nav-link">
					<input type="checkbox" class="custom-control-input" name="customColorMenuAdmin"  data-id="{{Auth::guard('web')->user()->id}}" {{Auth::guard('web')->user()->menu_color ? 'checked' : ''}} value="{{Auth::guard('web')->user()->menu_color}}" id="customColorMenuAdmin">
					<label class="custom-control-label link-title" for="customColorMenuAdmin">Dark</label>
			</div>
      </li>
      <li class="nav-item">
      <div class="custom-control custom-switch nav-link">
					<input type="checkbox" class="custom-control-input" name="customMenuAdmin" data-id="{{Auth::guard('web')->user()->id}}" {{Auth::guard('web')->user()->menu_contraido ? 'checked' : ''}} value="{{Auth::guard('web')->user()->menu_contraido}}" id="customMenuAdmin">
					<label class="custom-control-label link-title" for="customMenuAdmin">{{__('menu.sidebar_collapse')}}</label>
			</div>
      </li>
      <li class="nav-item nav-category"> <p class="name font-weight-bold mb-0">{{Auth::guard('web')->user()->name}}</p></li>
      <li class="nav-item">
        <a href="{{route('tenant.usuario.trocar.senha')}}" class="nav-link">
         <i class="link-icon" data-feather="edit"></i>
          <span class="link-title">{{__('auth.change_password')}}</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.logout') }}" class="nav-link"  
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();" >
         <i class="link-icon" data-feather="log-out"></i>
          <span class="link-title">Log Out</span>
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                    @csrf
        </form>
      </li>
    </ul>
  </div>
</nav>
