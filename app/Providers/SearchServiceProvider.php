<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Buku;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Menyediakan data 'listBukuGlobal' ke semua file blade
        View::composer('*', function ($view) {
            $view->with('listBukuGlobal', Buku::select('Judul')->distinct()->get());
        });
    }
}