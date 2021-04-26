<?php

namespace App\Http\Controllers\Parceiros;

use Auth;
use App\Models\Parceiro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth as FacadesAuth;


class ParceiroController extends Controller
{

    

    public function trocarDados(){

        $user = Auth::guard('parceiro')->user();

        return view('pages.parceiro.parceiro.trocar-senha', compact('user'));

    }

    public function atualizarDados(Request $request, $id){
         //valida o formulário
         $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:parceiros,email,'.$id.'|unique:tenants,email_administrador|unique:users,email',
            'password' => 'required|min:5'
        ], [
            'name.required' => 'Preencha o nome!',
            'password.required' => 'Senha não pode ficar em branco',
            'password.min' => 'A Senha deve conter no mínimo 5 caracteres',
            'email.required' => 'Preencha o e-mail do administrador',
            'email.unique' => 'E-mail já cadastrado',
            'email.email' => 'Insira um e-mail válido'
        ]);

        $dados = $request->all();
       
        $usuario = Parceiro::find($id);
        if ($usuario->password == $dados['password']) {
                unset($dados['password']);
            } else {
                $dados['password'] = bcrypt($dados['password']);
        }

        $usuario->update($dados);

        return redirect()->route('dashboard-parceiro')->with('success', 'Dados Atualizados com Sucesso!');

    }

    public function trocarSenhaInicial(){ 
        $parceiro = Auth::guard('parceiro')->user();
        $subdomain = explode('.', request()->getHost());
        $img = Parceiro::select('imagem_login')
            ->where('subdomain', $subdomain)->first();
        if(!$img){
                $imagem_login = 'logo-a2m.png';
                $tamanho_imagem = '75%';
                $background = 'bg.jpg';
        }else{
                $imagem_login = $img->imagem_login;
                $tamanho_imagem = $img->tamanho_imagem_login;
                $background = $img->fundo_imagem_login;
        }
        return view('pages.parceiro.parceiro.trocar-senha-inicial', compact('parceiro', 'imagem_login', 'tamanho_imagem', 'background'));
    }
    public function atualizarSenhaInicial(Request $request, $id){

         //valida o formulário
         $this->validate($request, [
               'password' => 'required|confirmed|min:5'
        ], [
            'password.required' => 'Senha não pode ficar em branco',
            'password.min' => 'A Senha deve conter no mínimo 5 caracteres',
            'password.confirmed' => 'As senhas não são iguais',
        ]);

        $dados = $request->all();
        $parceiro = Parceiro::find($id);
        $parceiro->update([
            'password' => bcrypt($dados['password']),
            'troca_senha' => 'N',
        ]);  
        if (Auth::guard('parceiro')->attempt(['email' => $parceiro->email, 'password' => $dados['password']])) {
         
                return redirect()->route('dashboard-parceiro')->with('toast_success', 'Bem Vindo!');
           
        }else{
        return redirect()->route('login');
        }
    }
}
