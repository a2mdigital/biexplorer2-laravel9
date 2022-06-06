<nav class="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      BI<span>EXPLORER</span>
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div> 
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>
      <li class="nav-item {{ active_class(['parceiro/admin']) }}">
        <a href="{{ route('dashboard-parceiro')}}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      @can('a2m')
      <li class="nav-item {{ active_class(['parceiro/admin/parceiros']) }}">
        <a href="{{ route('parceiros.listar') }}" class="nav-link">
         <i class="link-icon" data-feather="heart"></i>
          <span class="link-title">Parceiros</span>
        </a>
      </li>
      @endcan
      <li class="nav-item {{ active_class(['parceiro/admin/tenants']) }}">
        <a href="{{ route('parceiro.tenants') }}" class="nav-link">
         <i class="link-icon" data-feather="aperture"></i>
          <span class="link-title">Empresas</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['parceiro/admin/parceiro/powerbi']) }}">
        <a href="{{ route('parceiro.powerbi') }}" class="nav-link">
         <i class="link-icon" data-feather="pocket"></i>
          <span class="link-title">Power BI</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['parceiro/admin/grupos']) }}">
        <a href="{{ route('parceiro.gruposrelatorio') }}" class="nav-link">
         <i class="link-icon" data-feather="slack"></i>
          <span class="link-title">Relatórios</span>
        </a>
      </li>
      <li class="nav-item nav-category">{{__('menu.customize')}}</li>
      <!--
      <li class="nav-item {{ active_class(['parceiro/admin/grupos']) }}">
        <a href="{{ route('parceiro.customize') }}" class="nav-link">
         <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Personalizar</span>
        </a>
      </li>
      -->
      <li class="nav-item">
      <div class="custom-control custom-switch nav-link">
					<input type="checkbox" class="custom-control-input" name="customColorMenuParceiro" data-id="{{Auth::guard('parceiro')->user()->id}}" {{Auth::guard('parceiro')->user()->menu_color ? 'checked' : ''}} value="{{Auth::guard('parceiro')->user()->menu_color}}" id="customColorMenuParceiro">
					<label class="custom-control-label link-title" for="customColorMenuParceiro">Dark</label>
			</div>
      </li>
      <li class="nav-item">
      <div class="custom-control custom-switch nav-link">
					<input type="checkbox" class="custom-control-input" name="customMenuParceiro" data-id="{{Auth::guard('parceiro')->user()->id}}" {{Auth::guard('parceiro')->user()->menu_contraido ? 'checked' : ''}} value="{{Auth::guard('parceiro')->user()->menu_contraido}}" id="customMenuParceiro">
					<label class="custom-control-label link-title" for="customMenuParceiro">Menu Contraido</label>
			</div>
      </li>
      <li class="nav-item nav-category">Ajuda</li>
      <li class="nav-item {{ active_class(['parceiro/admin/parceiro/help']) }}">
        <a href="{{ route('parceiros.configuracao.help')}}" class="nav-link">
          <i class="link-icon" data-feather="help-circle"></i>
          <span class="link-title">Configuração Inicial</span>
        </a>
      </li>
  </div>
</nav>
