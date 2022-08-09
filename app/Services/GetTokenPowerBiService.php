<?php 

namespace App\Services;

use App\Models\PowerBiParceiro;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class GetTokenPowerBiService{
 
    public static function getToken(){
        $dadosPowerBi = PowerBiParceiro::get()->first();

        $user = $dadosPowerBi->user_powerbi;
        $password = Crypt::decryptString($dadosPowerBi->password_powerbi);
        $clientId = $dadosPowerBi->client_id;
        $clientSecret = $dadosPowerBi->client_secret;
        $diretorioId = $dadosPowerBi->diretorio_id;

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
            //dd($body);
            $token = $body['access_token'];
            $expires_in = $body['expires_in'];
            return ['resposta' => 'ok', 'token' => $token, 'expires_in' => $expires_in];
        } catch (ClientException $e) {
         
            $response = json_decode($e->getResponse()->getBody()->getContents(),true);
            dd($response);
            $error = json_decode($e->getResponse()->getBody()->getContents(), true);
         
            return ['resposta' => 'erro', 'error' => $error];
        
        }catch (ConnectException $e) {
           
            return ['resposta' => 'erro', 'error' => $e->getMessage()];
            //return response()->json(["resposta" => $e->getMessage()]);
        } 

//
    }
}