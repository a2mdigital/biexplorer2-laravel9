<?php 

namespace App\Services;

use App\Models\User;
use App\Models\Relatorio;
use App\Models\TenantUser;
use App\Models\PowerBiParceiro;
use Illuminate\Support\Facades\Crypt;
use App\Models\RelatorioUserPermission;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use App\Models\RelatorioDepartamentoPermission;

class GetTokenRlsPowerBiService{


public static function getTokenRlsTenant(Relatorio $relatorio, TenantUser $tenant){
        $dadosPowerBi = PowerBiParceiro::get()->first();

        $user = $dadosPowerBi->user_powerbi;
        $password = Crypt::decryptString($dadosPowerBi->password_powerbi);
        $clientId = $dadosPowerBi->client_id;
        $clientSecret = $dadosPowerBi->client_secret;
        $diretorioId = $dadosPowerBi->diretorio_id;

        /* DADOS DO RELATÓRIO PARA GERAÇÃO DO TOKEN RLS */
        $report_id = $relatorio->report_id;
        $dataset_id = $relatorio->dataset_id;
      
        $rls_regra[] = $tenant->regra_rls;
        $username_rls = $tenant->username_rls;
        if($username_rls == ''){
         $username_rls = 'a';
        }
 
        $client = new \GuzzleHttp\Client();
        /*
           URL AUTENTICAÇÃO AZURE
           A URL TEM COMO PARÂMETRO O ID DO DIRETÓRIO DO AZURE
        */
        $url_autenticacao = 'https://login.windows.net/' . $diretorioId . '/oauth2/token';
        try {
            /** @var GuzzleHttp\Client $client **/
            $response = $client->post(
               /*
                 FAZ UMA REQUISIÇÃO VIA POST PARA A URL ACIMA PASSANDO COMO PARÂMETRO
                 CLIENT_ID E CLIENT_SECRET DO POWER BI
                 USUÁRIO E SENHA DA CONTA PRÓ DO POWER BI
               */
                $url_autenticacao,
                [
                    "headers" => [
                        "Accept" => "application/json"
                    ],
                    'form_params' => [
                        'resource'      => 'https://analysis.windows.net/powerbi/api',
                        'client_id'     => $clientId,
                        'client_secret' => $clientSecret,
                        'grant_type'    => 'password',
                        'username'      => $user,
                        'password'      => $password,
                        'scope'         => 'openid',
                    ]
                ]
            );

                $body = json_decode($response->getBody()->getContents(), true);
                $token = $body['access_token'];
                //return ['resposta' => 'ok', 'token' => $token];
                //COM O TOKEN GERADO EU GERO OUTRO TOKEN RLS
                $clientRLS = new \GuzzleHttp\Client();
                $url_autenticacaoRLS = 'https://api.powerbi.com/v1.0/myorg/GenerateToken';
                try {
    
                    $responseRLS = $clientRLS->post(
    
                        $url_autenticacaoRLS,
                        [
                            "headers" => [
                                "Content-Type" => "application/json",
                                'Authorization' => 'Bearer ' . $token,
                            ],
                            'json' =>
                            array(
                                'datasets' =>
                                array(
                                    0 =>
                                    array(
                                        'id' => $dataset_id,
                                    ),
                                ),
                                'reports' =>
                                array(
                                    0 =>
                                    array(
                                        'id' => $report_id,
                                    ),
                                ),
                                'identities' =>
                                array(
                                    0 =>
                                    array(
                                        'username' =>  $username_rls,
                                        'roles' => $rls_regra,
                                        'datasets' =>
                                        array(
                                            0 => $dataset_id,
                                        ),
                                    ),
                                ),
                            )
                            
                        ]
                    );
                    $bodyRLS = json_decode($responseRLS->getBody()->getContents(), true);
                    $tokenRLS = $bodyRLS['token'];
                    return ['resposta' => 'ok', 'token' => $tokenRLS];
                } catch (ClientException $e) {
                   // dd($e->getResponse()->getBody()->getContents());
                    return ['resposta' => 'erro', 'error' => $e->getMessage()];
                    //return response()->json(["resposta" => $e->getMessage()]);
            }catch (ConnectException $e) {
                
                    return ['resposta' => 'erro', 'error' => $e->getMessage()];
                    //return response()->json(["resposta" => $e->getMessage()]);
            }   
        } catch (ClientException $e) {
         
            return ['resposta' => 'erro', 'error' => $e->getMessage()];
            //return response()->json(["resposta" => $e->getMessage()]);
        }catch (ConnectException $e) {
         
            return ['resposta' => 'erro', 'error' => $e->getMessage()];
            //return response()->json(["resposta" => $e->getMessage()]);
        }
}//FIM RLS POR TENANT

    //RLS POR RELATÓRIO - USUÁRIO
public static function getTokenRlsRelatorioUser(Relatorio $relatorio, RelatorioUserPermission $relatorio_user){
        $dadosPowerBi = PowerBiParceiro::get()->first();

        $user = $dadosPowerBi->user_powerbi;
        $password = Crypt::decryptString($dadosPowerBi->password_powerbi);
        $clientId = $dadosPowerBi->client_id;
        $clientSecret = $dadosPowerBi->client_secret;
        $diretorioId = $dadosPowerBi->diretorio_id;

        /* DADOS DO RELATÓRIO PARA GERAÇÃO DO TOKEN RLS */
        $report_id = $relatorio->report_id;
        $dataset_id = $relatorio->dataset_id;
      
        $rls_regra[] = $relatorio_user->regra_rls;
        $username_rls = $relatorio_user->username_rls;
        if($username_rls == ''){
         $username_rls = 'a';
        }
 

        $client = new \GuzzleHttp\Client();
        /*
           URL AUTENTICAÇÃO AZURE
           A URL TEM COMO PARÂMETRO O ID DO DIRETÓRIO DO AZURE
        */
        $url_autenticacao = 'https://login.windows.net/' . $diretorioId . '/oauth2/token';
        try {
            /** @var GuzzleHttp\Client $client **/
            $response = $client->post(
               /*
                 FAZ UMA REQUISIÇÃO VIA POST PARA A URL ACIMA PASSANDO COMO PARÂMETRO
                 CLIENT_ID E CLIENT_SECRET DO POWER BI
                 USUÁRIO E SENHA DA CONTA PRÓ DO POWER BI
               */
                $url_autenticacao,
                [
                    "headers" => [
                        "Accept" => "application/json"
                    ],
                    'form_params' => [
                        'resource'      => 'https://analysis.windows.net/powerbi/api',
                        'client_id'     => $clientId,
                        'client_secret' => $clientSecret,
                        'grant_type'    => 'password',
                        'username'      => $user,
                        'password'      => $password,
                        'scope'         => 'openid',
                    ]
                ]
            );

                $body = json_decode($response->getBody()->getContents(), true);
                $token = $body['access_token'];
                //return ['resposta' => 'ok', 'token' => $token];
                //COM O TOKEN GERADO EU GERO OUTRO TOKEN RLS
                $clientRLS = new \GuzzleHttp\Client();
                $url_autenticacaoRLS = 'https://api.powerbi.com/v1.0/myorg/GenerateToken';
                try {
    
                    $responseRLS = $clientRLS->post(
    
                        $url_autenticacaoRLS,
                        [
                            "headers" => [
                                "Content-Type" => "application/json",
                                'Authorization' => 'Bearer ' . $token,
                            ],
                            'json' =>
                            array(
                                'datasets' =>
                                array(
                                    0 =>
                                    array(
                                        'id' => $dataset_id,
                                    ),
                                ),
                                'reports' =>
                                array(
                                    0 =>
                                    array(
                                        'id' => $report_id,
                                    ),
                                ),
                                'identities' =>
                                array(
                                    0 =>
                                    array(
                                        'username' =>  $username_rls,
                                        'roles' => $rls_regra,
                                        'datasets' =>
                                        array(
                                            0 => $dataset_id,
                                        ),
                                    ),
                                ),
                            )
                            
                        ]
                    );
                    $bodyRLS = json_decode($responseRLS->getBody()->getContents(), true);
                    $tokenRLS = $bodyRLS['token'];
                    return ['resposta' => 'ok', 'token' => $tokenRLS];
                } catch (ClientException $e) {
                   // dd($e->getResponse()->getBody()->getContents());
                    return ['resposta' => 'erro', 'error' => $e->getMessage()];
                    //return response()->json(["resposta" => $e->getMessage()]);
            }catch (ConnectException $e) {
                
                    return ['resposta' => 'erro', 'error' => $e->getMessage()];
                    //return response()->json(["resposta" => $e->getMessage()]);
            }   
        } catch (ClientException $e) {
         
            return ['resposta' => 'erro', 'error' => $e->getMessage()];
            //return response()->json(["resposta" => $e->getMessage()]);
        }catch (ConnectException $e) {
         
            return ['resposta' => 'erro', 'error' => $e->getMessage()];
            //return response()->json(["resposta" => $e->getMessage()]);
        }
   }//FIM RLS POR RELATORIO USUARIO
//RLS POR RELATÓRIO - DEPARTAMENTO
public static function getTokenRlsRelatorioDepartamento(Relatorio $relatorio, RelatorioDepartamentoPermission $relatorios_departamento){
    $dadosPowerBi = PowerBiParceiro::get()->first();

    $user = $dadosPowerBi->user_powerbi;
    $password = Crypt::decryptString($dadosPowerBi->password_powerbi);
    $clientId = $dadosPowerBi->client_id;
    $clientSecret = $dadosPowerBi->client_secret;
    $diretorioId = $dadosPowerBi->diretorio_id;

    /* DADOS DO RELATÓRIO PARA GERAÇÃO DO TOKEN RLS */
    $report_id = $relatorio->report_id;
    $dataset_id = $relatorio->dataset_id;
  
    $rls_regra[] = $relatorios_departamento->regra_rls;
    $username_rls = $relatorios_departamento->username_rls;
    if($username_rls == ''){
     $username_rls = 'a';
    }


    $client = new \GuzzleHttp\Client();
    /*
       URL AUTENTICAÇÃO AZURE
       A URL TEM COMO PARÂMETRO O ID DO DIRETÓRIO DO AZURE
    */
    $url_autenticacao = 'https://login.windows.net/' . $diretorioId . '/oauth2/token';
    try {
        /** @var GuzzleHttp\Client $client **/
        $response = $client->post(
           /*
             FAZ UMA REQUISIÇÃO VIA POST PARA A URL ACIMA PASSANDO COMO PARÂMETRO
             CLIENT_ID E CLIENT_SECRET DO POWER BI
             USUÁRIO E SENHA DA CONTA PRÓ DO POWER BI
           */
            $url_autenticacao,
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                'form_params' => [
                    'resource'      => 'https://analysis.windows.net/powerbi/api',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type'    => 'password',
                    'username'      => $user,
                    'password'      => $password,
                    'scope'         => 'openid',
                ]
            ]
        );

            $body = json_decode($response->getBody()->getContents(), true);
            $token = $body['access_token'];
            //return ['resposta' => 'ok', 'token' => $token];
            //COM O TOKEN GERADO EU GERO OUTRO TOKEN RLS
            $clientRLS = new \GuzzleHttp\Client();
            $url_autenticacaoRLS = 'https://api.powerbi.com/v1.0/myorg/GenerateToken';
            try {

                $responseRLS = $clientRLS->post(

                    $url_autenticacaoRLS,
                    [
                        "headers" => [
                            "Content-Type" => "application/json",
                            'Authorization' => 'Bearer ' . $token,
                        ],
                        'json' =>
                        array(
                            'datasets' =>
                            array(
                                0 =>
                                array(
                                    'id' => $dataset_id,
                                ),
                            ),
                            'reports' =>
                            array(
                                0 =>
                                array(
                                    'id' => $report_id,
                                ),
                            ),
                            'identities' =>
                            array(
                                0 =>
                                array(
                                    'username' =>  $username_rls,
                                    'roles' => $rls_regra,
                                    'datasets' =>
                                    array(
                                        0 => $dataset_id,
                                    ),
                                ),
                            ),
                        )
                        
                    ]
                );
                $bodyRLS = json_decode($responseRLS->getBody()->getContents(), true);
                $tokenRLS = $bodyRLS['token'];
                return ['resposta' => 'ok', 'token' => $tokenRLS];
            } catch (ClientException $e) {
               // dd($e->getResponse()->getBody()->getContents());
                return ['resposta' => 'erro', 'error' => $e->getMessage()];
                //return response()->json(["resposta" => $e->getMessage()]);
        }catch (ConnectException $e) {
            
                return ['resposta' => 'erro', 'error' => $e->getMessage()];
                //return response()->json(["resposta" => $e->getMessage()]);
        }   
    } catch (ClientException $e) {
     
        return ['resposta' => 'erro', 'error' => $e->getMessage()];
        //return response()->json(["resposta" => $e->getMessage()]);
    }catch (ConnectException $e) {
     
        return ['resposta' => 'erro', 'error' => $e->getMessage()];
        //return response()->json(["resposta" => $e->getMessage()]);
    }
}//FIM RLS POR RELATORIO DEPARTAMENTO
//RLS POR USUÁRIO
public static function getTokenRlsUser(Relatorio $relatorio, User $user){
    $dadosPowerBi = PowerBiParceiro::get()->first();
 
    $user_powerbi = $dadosPowerBi->user_powerbi;
    $password = Crypt::decryptString($dadosPowerBi->password_powerbi);
    $clientId = $dadosPowerBi->client_id;
    $clientSecret = $dadosPowerBi->client_secret;
    $diretorioId = $dadosPowerBi->diretorio_id;

    /* DADOS DO RELATÓRIO PARA GERAÇÃO DO TOKEN RLS */
    $report_id = $relatorio->report_id;
    $dataset_id = $relatorio->dataset_id;
  
    $rls_regra[] = $user->regra_rls;
    $username_rls = $user->username_rls;
    if($username_rls == ''){
     $username_rls = 'a';
    }


    $client = new \GuzzleHttp\Client();
    /*
       URL AUTENTICAÇÃO AZURE
       A URL TEM COMO PARÂMETRO O ID DO DIRETÓRIO DO AZURE
    */
    $url_autenticacao = 'https://login.windows.net/' . $diretorioId . '/oauth2/token';
    try {
        /** @var GuzzleHttp\Client $client **/
        $response = $client->post(
           /*
             FAZ UMA REQUISIÇÃO VIA POST PARA A URL ACIMA PASSANDO COMO PARÂMETRO
             CLIENT_ID E CLIENT_SECRET DO POWER BI
             USUÁRIO E SENHA DA CONTA PRÓ DO POWER BI
           */
            $url_autenticacao,
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                'form_params' => [
                    'resource'      => 'https://analysis.windows.net/powerbi/api',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type'    => 'password',
                    'username'      => $user_powerbi,
                    'password'      => $password,
                    'scope'         => 'openid',
                ]
            ]
        );

            $body = json_decode($response->getBody()->getContents(), true);
            $token = $body['access_token'];
            //return ['resposta' => 'ok', 'token' => $token];
            //COM O TOKEN GERADO EU GERO OUTRO TOKEN RLS
            $clientRLS = new \GuzzleHttp\Client();
            $url_autenticacaoRLS = 'https://api.powerbi.com/v1.0/myorg/GenerateToken';
            try {

                $responseRLS = $clientRLS->post(

                    $url_autenticacaoRLS,
                    [
                        "headers" => [
                            "Content-Type" => "application/json",
                            'Authorization' => 'Bearer ' . $token,
                        ],
                        'json' =>
                        array(
                            'datasets' =>
                            array(
                                0 =>
                                array(
                                    'id' => $dataset_id,
                                ),
                            ),
                            'reports' =>
                            array(
                                0 =>
                                array(
                                    'id' => $report_id,
                                ),
                            ),
                            'identities' =>
                            array(
                                0 =>
                                array(
                                    'username' =>  $username_rls,
                                    'roles' => $rls_regra,
                                    'datasets' =>
                                    array(
                                        0 => $dataset_id,
                                    ),
                                ),
                            ),
                        )
                        
                    ]
                );
                $bodyRLS = json_decode($responseRLS->getBody()->getContents(), true);
                $tokenRLS = $bodyRLS['token'];
                return ['resposta' => 'ok', 'token' => $tokenRLS];
            } catch (ClientException $e) {
               // dd($e->getResponse()->getBody()->getContents());
                return ['resposta' => 'erro', 'error' => $e->getMessage()];
                //return response()->json(["resposta" => $e->getMessage()]);
        }catch (ConnectException $e) {
            
                return ['resposta' => 'erro', 'error' => $e->getMessage()];
                //return response()->json(["resposta" => $e->getMessage()]);
        }   
    } catch (ClientException $e) {
     
        return ['resposta' => 'erro', 'error' => $e->getMessage()];
        //return response()->json(["resposta" => $e->getMessage()]);
    }catch (ConnectException $e) {
     
        return ['resposta' => 'erro', 'error' => $e->getMessage()];
        //return response()->json(["resposta" => $e->getMessage()]);
    }
}//FIM RLS DO USUÁRIO

}