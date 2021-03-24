<h1>Login do Parceiro</h1>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('parceiro.logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
 
                                <form id="logout-form" action="{{ route('parceiro.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>