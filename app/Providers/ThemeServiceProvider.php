<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider{

    public function register(){

    }

    public function boot(){

        View::share('cor_fundo_menu_lateral', '#000000');
    }
}