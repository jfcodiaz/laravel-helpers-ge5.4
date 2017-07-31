<?php
namespace DevTics\LaravelHelpers;
use Illuminate\Support\ServiceProvider as SP;
use Illuminate\Foundation\AliasLoader;

class ServiceProvider extends SP {
    public function boot() {
        
    }
    
    public function register() {
        /* maatwebsite/exce 
        /* http://www.maatwebsite.nl/laravel-excel/docs */
        $this->app->register(\Maatwebsite\Excel\ExcelServiceProvider::class);
        AliasLoader::getInstance()->alias(\Maatwebsite\Excel\Facades\Excel::class, 'Excel');
        
        /* HTMLMin */
        $this->app->register(\GrahamCampbell\HTMLMin\HTMLMinServiceProvider::class);
        AliasLoader::getInstance()->alias("HTMLMin", \GrahamCampbell\HTMLMin\Facades\HTMLMin::class);
        
        /* Laroute */
        $this->app->register(\Lord\Laroute\LarouteServiceProvider::class);
    }
}