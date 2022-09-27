<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GruposController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoritosController;
use App\Http\Controllers\Administradores\RelatorioTenantController;
use App\Http\Controllers\Api\ControleUsuariosParceiro;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/




Route::group(['middleware' => ['apiJwt']], function(){
    //login usuários
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    //login parceiros
    Route::get('auth/parceiro/me', [AuthController::class, 'meParceiro']);
    Route::post('auth/parceiro/logout', [AuthController::class, 'logoutParceiro']);
    //rotas internas app
    Route::get('grupos', [GruposController::class, 'index']);
    Route::get('grupo/{id}/reports', [ReportsController::class, 'index']);
    Route::get('grupo/{id}/reports/{report_id}/view', [ReportsController::class, 'viewReport']);
    Route::get('favoritos', [FavoritosController::class, 'index']);
    Route::post('favoritos/save', [FavoritosController::class, 'save']);

    //VISUALIZAR RELATÓRIOS
    Route::get('/grupo/{grupo}/relatorio/{id}/visualizar',[RelatorioTenantController::class, 'visualizarRelatorio']);

    //ROTAS DE PARCEIROS
    Route::get('parceiro/usuarios/listar', [ControleUsuariosParceiro::class, 'getUsuariosParceiro']);

});
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/parceiro/login', [AuthController::class, 'loginParceiro']); 
 