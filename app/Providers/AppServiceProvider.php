<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation; // ✅ Tambahkan ini!

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
        Paginator::useBootstrap();

        Relation::morphMap([
            'frame' => \App\Models\Frame::class,
            'lensa_finish' => \App\Models\LensaFinish::class,
            'lensa_khusus' => \App\Models\LensaKhusus::class,
            'softlens' => \App\Models\Softlen::class,
            'accessory' => \App\Models\Accessories::class,
        ]);
    }
}
