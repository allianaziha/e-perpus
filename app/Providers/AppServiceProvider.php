<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ChartPinjam;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $chartPinjam = collect();

            if (auth()->check()) {
                $chartPinjam = ChartPinjam::with('buku')
                    ->where('user_id', auth()->id())
                    ->get();
            }

            $view->with('chartPinjam', $chartPinjam);
        });
    }

}
