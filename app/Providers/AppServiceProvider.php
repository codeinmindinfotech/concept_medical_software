<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Recall;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\UserService::class);
    }

    public function boot()
    {
        View::composer('backend.theme.header', function ($view) {
            $currentMonth = now()->month;
            $currentYear = now()->year;

            $monthlyRecallCount = Recall::whereMonth('recall_date', $currentMonth)
                ->whereYear('recall_date', $currentYear)
                ->count();

            $recallsThisMonth = Recall::whereMonth('recall_date', $currentMonth)
                ->whereYear('recall_date', $currentYear)
                ->orderBy('recall_date', 'asc')
                ->take(5) // limit to 5 latest recalls
                ->get();

            $view->with(compact('monthlyRecallCount', 'recallsThisMonth'));
        });
    }



}
