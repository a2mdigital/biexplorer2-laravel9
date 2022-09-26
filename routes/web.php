<?php

use App\Http\Controllers\A2m\ParceirosA2mController;
use App\Http\Controllers\Administradores\DashboardAdmController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Administradores\DashboardController;
use App\Http\Controllers\Parceiros\TenantController;
use App\Http\Controllers\Administradores\RelatorioTenantController;
use App\Http\Controllers\Parceiros\PowerBiController;
use App\Http\Controllers\Parceiros\ParceirosDashboardController;
use App\Http\Controllers\Parceiros\RelatorioController;
use App\Http\Controllers\Administradores\DepartamentoController;
use App\Http\Controllers\Administradores\PlaylistController;
use App\Http\Controllers\Administradores\PowerBiControllerAdmin;
use App\Http\Controllers\Administradores\UsuarioController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Parceiros\HelpController;
use App\Http\Controllers\Parceiros\ParceiroController;
use App\Http\Controllers\Parceiros\ThemeController;
use App\Http\Controllers\Users\DashboardUserController;
use App\Http\Controllers\Users\PlaylistUsersController;
use App\Http\Controllers\Users\RelatorioUsersController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/clinica/{key1}/{key2}', function($key1, $key2) {
    $user = Auth::attempt(['email' => $key1, 'password' => $key2]);
    if ($user) {
        return redirect()->route('dashboard-users')->with('toast_success', 'Bem Vindo!');
    }else{
        return 'inválido';
    }
});
*/
/*
Route::get('/', function () {
    return view('pages.auth.login');
});

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('form-login');
*/
Route::get('/', [LoginController::class, 'showFormLogin'])->name('form-login');
Route::get('/login', [LoginController::class, 'showFormLogin'])->name('form-login');
//logar novamente
Route::get('/login-novamente', function () {
    return view('pages.auth.login-novamente');
})->name('login-novamente');

Route::post('/login', [LoginController::class,'login'])->name('login');
Route::post('parceiro-logout', [LoginController::class,'parceiroLogout'])->name('parceiro.logout');

/*ESQUECEU A SENHA*/
Route::get('/forget-password', [ForgotPasswordController::class, 'getEmail'])->name('esqueceu-senha');
Route::post('/forget-password',[ForgotPasswordController::class, 'postEmail'] )->name('forget-password');
Route::get('/reset-password/{token}',[ResetPasswordController::class, 'getPassword']);
Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('reset-password');


//LOGIN PARA AJFALAVINHA FAZER PELA PLATAFORMA DELES
Route::post('/login/falavinha', [LoginController::class,'login']);
//LOGIN NOSSA FARMACIA
Route::post('/login/nossafarmacia', [LoginController::class,'login']);


//ROTAS DE PARCEIROS
Route::group(['prefix' => 'parceiro', 'middleware' => 'auth:parceiro'], function () {
    /*ROTAS DE AJUDA*/ 
    Route::get('/admin/parceiro/help', [HelpController::class, 'helpConfiguracao'])->name('parceiros.configuracao.help');

    /*ROTA DA A2M*/ 
    Route::get('/admin/parceiros', [ParceirosA2mController::class, 'listarParceiros'])->name('parceiros.listar');
    Route::get('/admin/parceiros/cadastrar', [ParceirosA2mController::class, 'cadastrarParceiro'])->name('parceiro.cadastrar');
    Route::post('/admin/parceiros/salvar', [ParceirosA2mController::class, 'salvarParceiro'])->name('parceiro.salvar');
    Route::get('/admin/parceiro/editar/{id}', [ParceirosA2mController::class, 'editarParceiro'])->name('parceiro.editar');
    Route::put('/admin/parceiro/atualizar/{id}',[ParceirosA2mController::class, 'atualizarParceiro'])->name('parceiro.atualizar');
    Route::delete('/admin/parceiros/excluir/{id}', [ParceirosA2mController::class, 'excluirParceiro'])->name('parceiro.excluir');
    Route::get('/admin/parceiros/empresas/{id}', [ParceirosA2mController::class, 'listarEmpresasParceiro'])->name('parceiro.tenants.listar');
    /*FIM ROTAS A2M*/ 
    Route::get('/admin/parceiro/trocar/senha/inicial', [ParceiroController::class, 'trocarSenhaInicial'])->name('parceiro.trocar.senha.inicial');
    Route::put('/admin/parceiro/atualizar/senha/inicial/{id}', [ParceiroController::class, 'atualizarSenhaInicial'])->name('parceiro.atualizar.senha.inicial');
    Route::get('/admin', [ParceirosDashboardController::class, 'indexDashboard'])->name('dashboard-parceiro');
    Route::put('/admin/custom-menu-color/salvar', [ParceirosDashboardController::class, 'salvarCustomMenuColor'])->name('parceiro.custommenu.color.salvar');
    Route::put('/admin/custom-menu-contraido/salvar', [ParceirosDashboardController::class, 'salvarCustomMenuContraido'])->name('parceiro.custommenu.contraido.salvar');
    
    /*ROTAS CUSTOMIZAÇÃO PARCEIRO*/
    Route::get('/admin/parceiro/tema/customize', [ThemeController::class, 'index'])->name('parceiro.customize');
    Route::get('/admin/parceiro/tema/customize-image', [ThemeController::class, 'trocarImagens'])->name('parceiro.customize.images');
    Route::get('/admin/parceiro/tema/editar', [ThemeController::class, 'indexCustomTema'])->name('parceiro.tema');
    
    
    /*ROTAS DO USUÁRIO PARCEIRO */
    Route::get('/admin/user/trocar-dados',[ParceiroController::class, 'trocarDados'])->name('parceiro.user.trocar.dados');
    Route::put('/admin/user/atualizar-dados/{id}',[ParceiroController::class, 'atualizarDados'])->name('parceiro.user.atualizar.dados');
    /*ROTAS DE TENANT*/
    Route::get('/admin/tenants', [TenantController::class, 'listarTenants'])->name('parceiro.tenants');
    Route::get('/admin/tenants/cadastro', [TenantController::class, 'cadastrarTenant'])->name('parceiro.tenant.cadastrar');
    Route::post('/admin/tenants/salvar', [TenantController::class, 'salvarTenant'])->name('parceiro.tenant.salvar');
    Route::get('/admin/tenants/editar/{id}', [TenantController::class, 'editarTenant'])->name('parceiro.tenant.editar');
    Route::put('/admin/tenants/atualizar/{id}', [TenantController::class, 'atualizarTenant'])->name('parceiro.tenant.atualizar');
    Route::delete('/admin/tenants/excluir/{id}', [TenantController::class, 'excluirTenant'])->name('parceiro.tenant.excluir');
    /*FIM ROTAS TENANT*/
    
    /*ROTAS DE CADASTRO POWER BI DO PARCEIRO*/
    Route::get('/admin/parceiro/powerbi', [PowerBiController::class, 'listarPowerBi'])->name('parceiro.powerbi');
    Route::get('/admin/parceiro/powerbi/cadastro', [PowerBiController::class, 'cadastrarPowerBi'])->name('parceiro.powerbi.cadastrar');
    Route::post('/admin/parceiro/powerbi/salvar', [PowerBiController::class, 'salvarPowerBi'])->name('parceiro.powerbi.salvar');
    Route::get('/admin/parceiro/powerbi/editar/{id}', [PowerBiController::class, 'editarPowerBi'])->name('parceiro.powerbi.editar');
    Route::put('/admin/parceiro/powerbi/atualizar/{id}', [PowerBiController::class, 'atualizarPowerBi'])->name('parceiro.powerbi.atualizar');
    Route::get('/admin/parceiro/powerbi/testar-conexao/', [PowerBiController::class, 'testarConexao'])->name('parceiro.powerbi.testarconexao');
    Route::post('/admin/parceiro/api/gerar-token/', [PowerBiController::class, 'gerarTokenApiA2m'])->name('parceiro.api.gerartoken');
     /*FIM ROTAS DE CADASTRO POWER BI*/

     /*ROTAS DE CADASTRO DO POWER BI DA EMPRESA 
     Route::get('/admin/parceiro/empresa/{id}/powerbi/cadastro', [PowerBiController::class, 'cadastrarPowerBiEmpresa'])->name('parceiro.empresa.powerbi.cadastrar');
     Route::put('/admin/parceiro/empresa/powerbi/salvar', [PowerBiController::class, 'salvarPowerBiEmpresa'])->name('parceiro.empresa.powerbi.salvar');
     /*FIM ROTAS DE CADASTRO DO POWER BI DA EMPRESA */

     /*ROTAS DE BUSCAR RELATÓRIOS DO POWER BI */
     Route::get('/admin/powerbi/buscarRelatorios/{workspace_id}', [PowerBiController::class, 'buscarRelatorios'])->name('parceiro.powerbi.buscarelatorios');
     Route::get('/admin/powerbi/buscarDashboards/{workspace_id}', [PowerBiController::class, 'buscarDashboards'])->name('parceiro.powerbi.buscarDashboards');
     /* FIM ROTAS BUSCAR RELATORIOS POWER BI*/

     /*ROTAS DE RELATÓRIO*/
     Route::get('/admin/grupos/buscar', [RelatorioController::class, 'buscarGrupos'])->name('parceiro.gruposrelatorio.buscar');
     Route::get('/admin/grupos', [RelatorioController::class, 'listarGrupos'])->name('parceiro.gruposrelatorio');
     Route::post('/admin/grupos/salvar', [RelatorioController::class, 'salvarGrupos'])->name('parceiro.gruposrelatorio.salvar');
     Route::delete('/admin/grupos/{id}/excluir', [RelatorioController::class, 'excluirGrupo'])->name('parceiro.gruposrelatorio.excluir');
     Route::put('/admin/grupos/atualizar', [RelatorioController::class, 'atualizarGrupo'])->name('parceiro.gruposrelatorio.atualizar');
     Route::get('/admin/grupo/{id}/subgrupos', [RelatorioController::class, 'listarSubGrupos'])->name('parceiro.subgrupos.relatorios');
     Route::post('/admin/subgrupo/salvar', [RelatorioController::class, 'salvarSubGrupo'])->name('parceiro.subgruporelatorio.salvar');
     Route::delete('/admin/subgrupo/{id}/excluir', [RelatorioController::class, 'excluirSubGrupo'])->name('parceiro.subgruporelatorio.excluir');
     Route::put('/admin/subgrupo/atualizar', [RelatorioController::class, 'atualizarSubGrupo'])->name('parceiro.subgruporelatorio.atualizar');
     Route::get('/admin/subgrupo/{id}/relatorios', [RelatorioController::class, 'listarRelatorios'])->name('parceiro.relatorios');
     Route::get('/admin/subgrupo/{id}/relatorio/cadastro/', [RelatorioController::class, 'cadastrarRelatorio'])->name('parceiro.relatorio.cadastrar');
     Route::post('/admin/subgrupo/relatorio/salvar', [RelatorioController::class, 'salvarRelatorio'])->name('parceiro.relatorio.salvar');
     Route::get('/admin/subgrupo/relatorio/{id}/editar/', [RelatorioController::class, 'editarRelatorio'])->name('parceiro.relatorio.editar');
     Route::put('/admin/subgrupo/relatorio/{id}/atualizar/', [RelatorioController::class, 'atualizarRelatorio'])->name('parceiro.relatorio.atualizar');
     Route::delete('/admin/subgrupo/{subgrupo}/relatorio/{id}/excluir', [RelatorioController::class, 'excluirRelatorio'])->name('parceiro.relatorio.excluir');
     /*FIM ROTAS RELATORIO */

     /*ROTAS PERMISSÃO DOS RELATÓRIOS*/
     Route::get('/admin/relatorio/{id}/permissoes', [RelatorioController::class, 'permissaoRelatorio'])->name('parceiro.relatorio.permissao');
     Route::post('/admin/relatorio/salvar/permissoes', [RelatorioController::class, 'salvarPermissaoRelatorio'])->name('parceiro.relatorio.permissao.salvar');
     Route::delete('/admin/relatorio/{relatorio}/permissao/tenant/{id}/excluir', [RelatorioController::class, 'excluirPermissaoRelatorio'])->name('parceiro.relatorio.permissao.excluir');
     /*FIM ROTAS PERMISSAO RELATORIOS*/
});
//ROTAS DE ADMINISTRADORES
Route::group(['prefix' => 'admin', 'middleware' => ['auth:web','admin', 'checksinglesession']], function () { 

    Route::get('teste-relatorio-novo', function () {
        return view('pages.teste');
    })->name('teste.relatorio');

 Route::post('admin-logout', [LoginController::class,'adminLogout'])->name('admin.logout');   
 Route::get('/dashboard', [DashboardAdmController::class, 'indexDashboard'])->name('dashboard-admin');
 Route::put('/tenant/custom-menu-color/salvar', [DashboardAdmController::class, 'salvarCustomMenuColor'])->name('tenant.custommenu.color.salvar');
 Route::put('/tenant/custom-menu-contraido/salvar', [DashboardAdmController::class, 'salvarCustomMenuContraido'])->name('tenant.custommenu.contraido.salvar');
   
 /*TROCAR SENHA ADMINISTRADOR */
  Route::get('/tenant/usuarios/trocar-senha', [UsuarioController::class, 'trocarSenha'])->name('tenant.usuario.trocar.senha');
  Route::put('/tenant/usuarios/senha/atualizar/{id}',[UsuarioController::class, 'atualizarSenha'])->name('tenant.usuario.atualizar.senha');
  /*ROTAS DE USUÁRIOS*/
  Route::get('/tenant/usuarios', [UsuarioController::class, 'listarUsuarios'])->name('tenant.usuarios');
  Route::get('/tenant/usuario/cadastro', [UsuarioController::class, 'cadastrarUsuario'])->name('tenant.usuario.cadastrar');
  Route::post('/tenants/usuario/salvar', [UsuarioController::class, 'salvarUsuario'])->name('tenant.usuario.salvar');
  Route::get('/tenant/usuario/{id}/editar', [UsuarioController::class, 'editarUsuario'])->name('tenant.usuario.editar');
  Route::put('/tenant/usuario/{id}/atualizar', [UsuarioController::class, 'atualizarUsuario'])->name('tenant.usuario.atualizar');
  Route::delete('/tenant/usuario/{id}/excluir', [UsuarioController::class, 'excluirUsuario'])->name('tenant.usuario.excluir');  
  /*FIM ROTAS USUARIOS*/

  /*ROTAS DE DEPARTAMENTOS*/
  Route::get('/tenant/departamentos', [DepartamentoController::class, 'listarDepartamentos'])->name('tenant.departamentos');
  Route::get('/tenant/departamento/cadastro', [DepartamentoController::class, 'cadastrarDepartamento'])->name('tenant.departamento.cadastrar');
  Route::post('/tenants/departamento/salvar', [DepartamentoController::class, 'salvarDepartamento'])->name('tenant.departamento.salvar');
  Route::get('/tenant/departamento/{id}/editar', [DepartamentoController::class, 'editarDepartamento'])->name('tenant.departamento.editar');
  Route::put('/tenant/departamento/{id}/atualizar', [DepartamentoController::class, 'atualizarDepartamento'])->name('tenant.departamento.atualizar');
  Route::delete('/tenant/departamento/{id}/excluir', [DepartamentoController::class, 'excluirDepartamento'])->name('tenant.departamento.excluir');  
  /*FIM ROTAS DEPARTAMENTOS*/

  /*ROTAS DE RELATÓRIO*/
  Route::get('/tenant/grupos', [RelatorioTenantController::class, 'listarGrupos'])->name('tenant.gruposrelatorio');
  Route::get('/tenant/grupo/{id}/relatorios', [RelatorioTenantController::class, 'listarRelatorios'])->name('tenant.relatorios');
  Route::get('/tenant/grupo/{grupo}/relatorio/{id}/visualizar',[RelatorioTenantController::class, 'visualizarRelatorio'])->name('tenant.relatorios.visualizar');
  /*FIM ROTAS RELATORIO */

  /*ROTAS PERMISSÃO DOS RELATÓRIOS USUARIOS*/
   Route::get('/tenant/relatorio/{id}/permissoes/usuarios', [RelatorioTenantController::class, 'permissaoRelatorioUsuarios'])->name('tenant.relatorio.permissao.usuarios');
   Route::post('/tenant/relatorio/salvar/permissoes/usuarios', [RelatorioTenantController::class, 'salvarPermissaoRelatorioUsuarios'])->name('tenant.relatorio.permissao.usuarios.salvar');
   Route::delete('/tenant/relatorio/{relatorio}/permissao/usuario/{id}/excluir', [RelatorioTenantController::class, 'excluirPermissaoRelatorio'])->name('tenant.relatorio.permissao.usuarios.excluir');
   /*ROTAS PERMISSÃO DOS RELATÓRIOS DEPARTAMENTOS*/
   Route::get('/tenant/relatorio/{id}/permissoes/departamentos', [RelatorioTenantController::class, 'permissaoRelatorioDepartamentos'])->name('tenant.relatorio.permissao.departamentos');
   Route::post('/tenant/relatorio/salvar/permissoes/departamento', [RelatorioTenantController::class, 'salvarPermissaoRelatorioDepartamento'])->name('tenant.relatorio.permissao.departamento.salvar');
   Route::delete('/tenant/relatorio/{relatorio}/permissao/departamento/{id}/excluir', [RelatorioTenantController::class, 'excluirPermissaoRelatorioDepartamento'])->name('tenant.relatorio.permissao.departamento.excluir');
  
   /*FIM ROTAS PERMISSAO RELATORIOS*/

   /*ROTA TOKEN POWER BI*/ 
   Route::get('/tenant/powerbi/getTokenPowerBi', [PowerBiControllerAdmin::class, 'getToken']);
   /*FIM ROTA TOKEN POWER BI*/

   /*ROTAS DE PLAYLIST*/
   Route::get('/tenant/playlists', [PlaylistController::class, 'listarPlaylists'])->name('tenant.playlists');
   Route::get('/tenant/playlists/adicionar', [PlaylistController::class, 'cadastrarPlaylist'])->name('tenant.playlist.cadastrar');
   Route::post('/tenant/playlists/salvar', [PlaylistController::class, 'salvarPlaylist'])->name('tenant.playlist.salvar'); 
   Route::get('/tenant/playlists/editar/{id}', [PlaylistController::class, 'editarPlaylist'])->name('tenant.playlist.editar');
   Route::delete('/tenant/playlists/excluir/{id}', [PlaylistController::class, 'excluirPlaylist'])->name('tenant.playlist.excluir');
   Route::get('/tenant/playlists/visualizar/{id}', [PlaylistController::class, 'visualizarPlaylist'])->name('tenant.playlist.visualizar');
});
/* FIM ROTAS ADMINISTRADORES */

//ROTAS DE USUÁRIOS
Route::group(['prefix' => 'users','middleware' => ['auth:web','checksinglesession']], function () {
    Route::post('user-logout', [LoginController::class,'userLogout'])->name('user.logout');
    Route::get('/dashboard', [DashboardUserController::class, 'indexDashboard'])->name('dashboard-users');
    Route::put('/tenant/user/custom-menu-color/salvar', [DashboardUserController::class, 'salvarCustomMenuColor'])->name('users.tenant.custommenu.color.salvar');
    Route::put('/tenant/user/custom-menu-contraido/salvar', [DashboardUserController::class, 'salvarCustomMenuContraido'])->name('users.tenant.custommenu.contraido.salvar');
    Route::put('/tenant/user/favorito/salvar', [DashboardUserController::class, 'salvarFavorito'])->name('users.tenant.favorito.salvar');
  
  /*TROCAR SENHA INICIAL*/
  Route::get('/tenant/user/trocar/senha/inicial', [DashboardUserController::class, 'trocarSenhaInicial'])->name('users.tenant.trocar.senha.inicial');
  Route::put('/tenant/user/atualizar/senha/inicial/{id}', [DashboardUserController::class, 'atualizarSenhaInicial'])->name('users.tenant.atualizar.senha.inicial');
  /*TROCAR SENHA USUÁRIO */
  Route::get('/tenant/user/trocar-senha', [DashboardUserController::class, 'trocarSenha'])->name('users.tenant.trocar.senha');
  Route::put('/tenant/user/senha/atualizar/{id}',[DashboardUserController::class, 'atualizarSenha'])->name('users.tenant.atualizar.senha');
  /*ROTAS DE RELATÓRIO*/ 
  Route::get('/tenant/grupos', [RelatorioUsersController::class, 'listarGrupos'])->name('users.tenant.gruposrelatorio');
  Route::get('/tenant/grupo/{id}/relatorios', [RelatorioUsersController::class, 'listarRelatorios'])->name('users.tenant.relatorios');
  Route::get('/tenant/grupo/{grupo}/relatorio/{id}', [RelatorioUsersController::class, 'visualizarRelatorio'])->name('users.tenant.relatorios.visualizar');
  /*FIM ROTAS RELATORIO */
  /*ROTAS DE PLAYLIST*/
  Route::get('/tenant/user/playlists', [PlaylistUsersController::class, 'listarPlaylists'])->name('users.tenant.playlists');
  Route::get('/tenant/user/playlist/visualizar/{id}', [PlaylistUsersController::class, 'visualizarPlaylist'])->name('users.tenant.playlist.visualizar');
  /*FIM ROTAS PLAYLISTS */
  /*ROTA TOKEN POWER BI*/ 
  Route::get('/tenant/user/powerbi/getTokenPowerBi', [PowerBiControllerAdmin::class, 'getToken'])->name('users.tentant.powerbi.getToken');

  /*FIM ROTA TOKEN POWER BI*/
});
/*FIM ROTAS USUÁRIOS */
/*
Route::group(['prefix' => 'login', 'middleware' => ['auth:web']], function () {
    Route::get('pre-login', [LoginController::class,'preLogin'])->name('pre-login');   
});
*/
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
