<?php 

namespace App\Http\Controllers\Api;

Use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller{
    public function index(){
        return ['resposta' => 'ok', 'report' => 'teste'];
    }
}