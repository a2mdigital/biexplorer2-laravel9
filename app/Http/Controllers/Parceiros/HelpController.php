<?php

namespace App\Http\Controllers\Parceiros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function helpConfiguracao(){

        return view('pages.parceiro.help.help');
    }
}
