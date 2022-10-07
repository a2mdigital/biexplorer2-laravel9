<?php

namespace App\Http\Controllers\Privacidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoliticaDePrivacidadeController extends Controller
{
    public function index(){
        return view('pages.politica-privacidade.index');
    }
}
