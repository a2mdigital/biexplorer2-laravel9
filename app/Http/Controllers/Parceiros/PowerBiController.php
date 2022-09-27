<?php

namespace App\Http\Controllers\Parceiros;

use App\Models\Parceiro;
use Illuminate\Http\Request;
use App\Models\PowerBiParceiro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\GetTokenPowerBiService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;

class PowerBiController extends Controller
{
    public function listarPowerBi(){

        $powerbi = PowerBiParceiro::get();
        $user = Auth::guard('parceiro')->user();
     
        
        return view('pages.parceiro.powerbi-parceiro.listar', compact('powerbi', 'user'));
    }

    public function cadastrarPowerBi(){

        return view('pages.parceiro.powerbi-parceiro.cadastrar');
    }

    public function salvarPowerBi(Request $request){

        //valida o formulario
       $this->validate($request, [
        'user_powerbi' => 'required',
        'password_powerbi' => 'required',
        'client_id' => 'required',
        'client_secret' => 'required',
        'diretorio_id' => 'required',
        ], [
            'user_powerbi.required' => 'Usuário Obrigatório',
            'password_powerbi.required' => 'Senha não pode ficar em branco',
            'client_id.required' => 'Campo Obrigatório',
            'client_secret.required' => 'Campo Obrigatório',
            'diretorio_id.required' => 'Campo Obrigatório'
        ]);

        $dados = $request->all();
        
        PowerBiParceiro::create([
            'user_powerbi' => $dados['user_powerbi'],
            'password_powerbi' => Crypt::encryptString($dados['password_powerbi']),
            'client_id' => $dados['client_id'],
            'client_secret' => $dados['client_secret'],
            'diretorio_id' => $dados['diretorio_id']
        ]);

        return redirect()->route('parceiro.powerbi')->with('success', 'Conta Cadastrada com sucesso!');

    }

    public function editarPowerBi($id){
        $powerbi = PowerBiParceiro::findOrFail($id);
      
     
        return view('pages.parceiro.powerbi-parceiro.editar', compact('powerbi'));
    }

    public function atualizarPowerBi(Request $request, $id){
        
        $powerbi = PowerBiParceiro::find($id);
        $dados = $request->all();
         //valida o formulario
       $this->validate($request, [
        'user_powerbi' => 'required',
        'password_powerbi' => 'required',
        'client_id' => 'required',
        'client_secret' => 'required',
        'diretorio_id' => 'required',
        ], [
            'user_powerbi.required' => 'Usuário Obrigatório',
            'password_powerbi.required' => 'Senha não pode ficar em branco',
            'client_id.required' => 'Campo Obrigatório',
            'client_secret.required' => 'Campo Obrigatório',
            'diretorio_id.required' => 'Campo Obrigatório'
        ]);

        if ($powerbi->password_powerbi == $dados['password_powerbi']) {
            unset($dados['password_powerbi']);
        } else {
            $dados['password_powerbi'] = Crypt::encryptString($dados['password_powerbi']);
        }

  
        $powerbi->update($dados);    

        return redirect()->route('parceiro.powerbi')->with('toast_success', 'Conta atualizada com sucesso!');
    }

    public function testarConexao(){
       //testar conexão power bi
        if(Auth::guard('parceiro')->check()){
            $parceiro_id = auth()->user()->id;
          
        }
       
        $dadosPowerBi = PowerBiParceiro::withoutGlobalScopes()->where('parceiro_id',$parceiro_id)->first();  
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
       // dd($body);     
        return ['resposta' => 'ok', 'msg' => $body];
        } catch (ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents(), true);
            return ['resposta' => 'erro', 'msg' => $error['error_description']];
        }catch (ConnectException $e) {
            
            return ['resposta' => 'erro', 'msg' => $e->getMessage()];
        }
       
    }

    public function gerarTokenApiA2m(Request $request){
 
        return 'teste';
        $dados = $request->all();
        $email = $dados['email'];
        $password = $dados['password'];
        $parceiro_id = $dados['parceiro_id'];
        $powerbi = PowerBiParceiro::find($parceiro_id);

        $client = new \GuzzleHttp\Client();
        $url_autenticacao = 'https://dados.app.br/api/auth/parceiro/login';
        try {
            $response = $client->post(
                $url_autenticacao,
                [
                    'form_params' => [
                        'email'      => $email,
                        'password'   => $password,
                      
                    ]
                ]
            );

         $dados = json_decode($response->getBody()->getContents(), true);
         $token = $dados['access_token'];
         $expira_em = date("y-m-d", strtotime($dados['expires_in']));
         $expira_em_formatado = date("d-m-Y", strtotime($dados['expires_in']));
         $powerbi = PowerBiParceiro::find($parceiro_id);
         $atualizar['bearer_token_api_a2m'] = $token;
         $atualizar['data_expira_token'] = $expira_em;
        
         $powerbi->update($atualizar);    
         return ['resposta' => 'ok', 'token' => $token, 'expira_em' => $expira_em_formatado];



        }catch (ClientException $e) {
            return ['resposta' => 'erro', 'msg' => $e->getMessage()];
            //return ['resposta' => 'erro', 'dados' => 'Não foi possível gerar o Token'];
        
        }
        
    }


    /*PEGAR RELATÓRIOS DO POWER BI */ 
    public function buscarRelatorios($workspace_id){
  
        $dados_powerBiAzure = PowerBiParceiro::get();
        foreach ($dados_powerBiAzure as $dadosPBA) {
            $userPowerBI = $dadosPBA['user_powerbi'];
            $passPowerBI = Crypt::decryptString($dadosPBA['password_powerbi']);
            $clientIdAzure = $dadosPBA['client_id'];
            $clientSecretAzure = $dadosPBA['client_secret'];
            $diretorioIdAzure = $dadosPBA['diretorio_id'];
        }

        $client = new \GuzzleHttp\Client();
        $url_autenticacao = 'https://login.windows.net/' . $diretorioIdAzure . '/oauth2/token';
        try {
            /** @var GuzzleHttp\Client $client **/
            $response = $client->post(
                //'https://login.windows.net/896fc9ac-5684-488b-9f0c-58b7f50b46ee/oauth2/token',
                $url_autenticacao,
                [
                    "headers" => [
                        "Accept" => "application/json"
                    ],
                    'form_params' => [
                        'resource'      => 'https://analysis.windows.net/powerbi/api',
                        'client_id'     => $clientIdAzure,
                        'client_secret' => $clientSecretAzure,
                        'grant_type'    => 'password',
                        'username'      => $userPowerBI,
                        'password'      => $passPowerBI,
                        'scope'         => 'openid',
                    ]
                ]
            );

            $body = json_decode($response->getBody()->getContents(), true);

            $token = $body['access_token'];

            $client2 = new \GuzzleHttp\Client();
            $res = $client2->request(
                'GET',
                'https://api.powerbi.com/v1.0/myorg/groups/'.$workspace_id. '/reports',
                [
                    'headers' =>
                    [
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-type' => 'application/json'
                    ]
                ]
            );

            $relatorios = json_decode($res->getBody()->getContents(), true);
            return response()->json($relatorios["value"]);
            /*
            foreach ($workspace["value"] as $work) {
                echo $work['name'];
                echo '<br>';
            }
            */
        } catch (ClientException $e) {
            return redirect()->back()->with('toast_error', 'Não foi possível se conectar com o Power BI, verifique as configurações!');
            /*
            return ['error' => $e->getMessage()];
            return response()->json(["resposta" => $e->getMessage()]);
            */
        }
      


    }  
    //PEGAR RELATÓRIOS PAGINA DE EDIÇÃO
        /*PEGAR RELATÓRIOS DO POWER BI */ 
        public function buscarRelatoriosEditar($workspace_id){
  
            $dados_powerBiAzure = PowerBiParceiro::get();
            foreach ($dados_powerBiAzure as $dadosPBA) {
                $userPowerBI = $dadosPBA['user_powerbi'];
                $passPowerBI = Crypt::decryptString($dadosPBA['password_powerbi']);
                $clientIdAzure = $dadosPBA['client_id'];
                $clientSecretAzure = $dadosPBA['client_secret'];
                $diretorioIdAzure = $dadosPBA['diretorio_id'];
            }
    
            $client = new \GuzzleHttp\Client();
            $url_autenticacao = 'https://login.windows.net/' . $diretorioIdAzure . '/oauth2/token';
            try {
                /** @var GuzzleHttp\Client $client **/
                $response = $client->post(
                    //'https://login.windows.net/896fc9ac-5684-488b-9f0c-58b7f50b46ee/oauth2/token',
                    $url_autenticacao,
                    [
                        "headers" => [
                            "Accept" => "application/json"
                        ],
                        'form_params' => [
                            'resource'      => 'https://analysis.windows.net/powerbi/api',
                            'client_id'     => $clientIdAzure,
                            'client_secret' => $clientSecretAzure,
                            'grant_type'    => 'password',
                            'username'      => $userPowerBI,
                            'password'      => $passPowerBI,
                            'scope'         => 'openid',
                        ]
                    ]
                );
    
                $body = json_decode($response->getBody()->getContents(), true);
    
                $token = $body['access_token'];
    
                $client2 = new \GuzzleHttp\Client();
                $res = $client2->request(
                    'GET',
                    'https://api.powerbi.com/v1.0/myorg/groups/'.$workspace_id. '/reports',
                    [
                        'headers' =>
                        [
                            'Authorization' => 'Bearer ' . $token,
                            'Accept' => 'application/json',
                            'Content-type' => 'application/json'
                        ]
                    ]
                );
    
                $relatorios = json_decode($res->getBody()->getContents(), true);
                return $relatorios["value"];
                /*
                foreach ($workspace["value"] as $work) {
                    echo $work['name'];
                    echo '<br>';
                }
                */
            } catch (ClientException $e) {
                return redirect()->back()->with('toast_error', 'Não foi possível se conectar com o Power BI, verifique as configurações!');
                /*
                return ['error' => $e->getMessage()];
                return response()->json(["resposta" => $e->getMessage()]);
                */
            }
          
    
    
        }  
   /*FIM PEGAR RELATÓRIOS DO POWER BI */

   /*BUSCAR DASHBOARDS */

   public function buscarDashboards($workspace_id)
   {
        $dados_powerBiAzure = PowerBiParceiro::get();
        foreach ($dados_powerBiAzure as $dadosPBA) {
            $userPowerBI = $dadosPBA['user_powerbi'];
            $passPowerBI = Crypt::decryptString($dadosPBA['password_powerbi']);
            $clientIdAzure = $dadosPBA['client_id'];
            $clientSecretAzure = $dadosPBA['client_secret'];
            $diretorioIdAzure = $dadosPBA['diretorio_id'];
        }

       $client = new \GuzzleHttp\Client();
       $url_autenticacao = 'https://login.windows.net/' . $diretorioIdAzure . '/oauth2/token';
       try {
           /** @var GuzzleHttp\Client $client **/
           $response = $client->post(
               //'https://login.windows.net/896fc9ac-5684-488b-9f0c-58b7f50b46ee/oauth2/token',
               $url_autenticacao,
               [
                   "headers" => [
                       "Accept" => "application/json"
                   ],
                   'form_params' => [
                       'resource'      => 'https://analysis.windows.net/powerbi/api',
                       'client_id'     => $clientIdAzure,
                       'client_secret' => $clientSecretAzure,
                       'grant_type'    => 'password',
                       'username'      => $userPowerBI,
                       'password'      => $passPowerBI,
                       'scope'         => 'openid',
                   ]
               ]
           );

           $body = json_decode($response->getBody()->getContents(), true);

           $token = $body['access_token'];

           $client2 = new \GuzzleHttp\Client();
           $res = $client2->request(
               'GET',
               'https://api.powerbi.com/v1.0/myorg/groups/' . $workspace_id . '/dashboards',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer ' . $token,
                       'Accept' => 'application/json',
                       'Content-type' => 'application/json'
                   ]
               ]
           );

           $dashboards = json_decode($res->getBody()->getContents(), true);
           return response()->json($dashboards["value"]);
           /*
           foreach ($workspace["value"] as $work) {
               echo $work['name'];
               echo '<br>';
           }
           */
       } catch (ClientException $e) {
           return redirect()->back()->with('toast_error', 'Não foi possível se conectar com o Power BI, verifique as configurações!');
           /*
           return ['error' => $e->getMessage()];
           return response()->json(["resposta" => $e->getMessage()]);
           */
       }
       //$workspace = Relatorio::orderBy('desc_relatorio', 'asc')->get();


   }

   public function buscarDashboardsEditar($workspace_id)
   {
        $dados_powerBiAzure = PowerBiParceiro::get();
        foreach ($dados_powerBiAzure as $dadosPBA) {
            $userPowerBI = $dadosPBA['user_powerbi'];
            $passPowerBI = Crypt::decryptString($dadosPBA['password_powerbi']);
            $clientIdAzure = $dadosPBA['client_id'];
            $clientSecretAzure = $dadosPBA['client_secret'];
            $diretorioIdAzure = $dadosPBA['diretorio_id'];
        }

       $client = new \GuzzleHttp\Client();
       $url_autenticacao = 'https://login.windows.net/' . $diretorioIdAzure . '/oauth2/token';
       try {
           /** @var GuzzleHttp\Client $client **/
           $response = $client->post(
               //'https://login.windows.net/896fc9ac-5684-488b-9f0c-58b7f50b46ee/oauth2/token',
               $url_autenticacao,
               [
                   "headers" => [
                       "Accept" => "application/json"
                   ],
                   'form_params' => [
                       'resource'      => 'https://analysis.windows.net/powerbi/api',
                       'client_id'     => $clientIdAzure,
                       'client_secret' => $clientSecretAzure,
                       'grant_type'    => 'password',
                       'username'      => $userPowerBI,
                       'password'      => $passPowerBI,
                       'scope'         => 'openid',
                   ]
               ]
           );

           $body = json_decode($response->getBody()->getContents(), true);

           $token = $body['access_token'];

           $client2 = new \GuzzleHttp\Client();
           $res = $client2->request(
               'GET',
               'https://api.powerbi.com/v1.0/myorg/groups/' . $workspace_id . '/dashboards',
               [
                   'headers' =>
                   [
                       'Authorization' => 'Bearer ' . $token,
                       'Accept' => 'application/json',
                       'Content-type' => 'application/json'
                   ]
               ]
           );

           $dashboards = json_decode($res->getBody()->getContents(), true);
           return $dashboards["value"];
           /*
           foreach ($workspace["value"] as $work) {
               echo $work['name'];
               echo '<br>';
           }
           */
       } catch (ClientException $e) {
           return redirect()->back()->with('toast_error', 'Não foi possível se conectar com o Power BI, verifique as configurações!');
           /*
           return ['error' => $e->getMessage()];
           return response()->json(["resposta" => $e->getMessage()]);
           */
       }
       //$workspace = Relatorio::orderBy('desc_relatorio', 'asc')->get();


   }
   /* FIM BUSCAR DASHBOARDS */

       /*CADASTRO DO POWER BI DA EMPRESA
    public function cadastrarPowerBiEmpresa($id){
        $tenant = Tenant::find($id);
        $powerbi = PowerBiEmpresa::where('tenant_id', '=', $tenant->id)->first();
       
        return view('pages.parceiro.powerbi-empresa.cadastrar', compact('tenant', 'powerbi'));
    }

    public function salvarPowerBiEmpresa(Request $request){
      
        $dados = $request->all();
         //valida o formulario
       $this->validate($request, [
        'user_powerbi' => 'required',
        'password_powerbi' => 'required',
        'client_id' => 'required',
        'client_secret' => 'required',
        'diretorio_id' => 'required',
        ], [
            'user_powerbi.required' => 'Usuário Obrigatório',
            'password_powerbi.required' => 'Senha não pode ficar em branco',
            'client_id.required' => 'Campo Obrigatório',
            'client_secret.required' => 'Campo Obrigatório',
            'diretorio_id.required' => 'Campo Obrigatório'
        ]);
        try{
            $password = Crypt::decryptString($dados['password_powerbi']);
        }catch (DecryptException $e) {
           
        }    
        $powerbi = PowerBiEmpresa::where('tenant_id', '=', $dados['tenant_id'])->first();
        if(!$powerbi){
            PowerBiEmpresa::create([
                'user_powerbi' => $dados['user_powerbi'],
                'password_powerbi' => Crypt::encryptString($dados['password_powerbi']),
                'client_id' => $dados['client_id'],
                'client_secret' => $dados['client_secret'],
                'diretorio_id' => $dados['diretorio_id'],
                'tenant_id' => $dados['tenant_id']
            ]);
           
        }else{
            //atualizar
            if ($powerbi->password_powerbi == $dados['password_powerbi']) {
                unset($dados['password_powerbi']);
            } else {
                $dados['password_powerbi'] = Crypt::encryptString($dados['password_powerbi']);
            }

            $powerbi->update($dados);    

        }
       
        return redirect()->route('parceiro.tenants')->with('success', 'Conta atualizada com sucesso!');

    }
     FIM CADASTRO POWER BI EMPRESA */


}
