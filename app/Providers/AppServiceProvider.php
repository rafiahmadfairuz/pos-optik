<?php

namespace App\Providers;

use App\Models\Frame;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        // if(config('app.env') === 'local'){
        //     URL::forceScheme('https');
        // }
        Paginator::useBootstrap();

        Relation::morphMap([
            'frame' => Frame::class,
            'lensa_finish' => \App\Models\LensaFinish::class,
            'lensa_khusus' => \App\Models\LensaKhusus::class,
            'softlens' => \App\Models\Softlen::class,
            'accessory' => \App\Models\Accessories::class,
        ]);
    }
}
