<?php

namespace App\Http\Controllers\Parceiros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index(){
        return view('pages.parceiro.tema.customize');
    }
    public function trocarImagens(){
        return view('pages.parceiro.tema.customize-img');
    }
    public function indexCustomTema(){
        return view('pages.parceiro.tema.tema');
    }
}
