<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Recall;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\UserService::class);
    }

    public function boot()
    {
        View::composer('backend.theme.header', function ($view) {
            $user = Auth::user();
            $currentMonth = now()->month;
            $currentYear = now()->year;

            $query = Recall::whereMonth('recall_date', $currentMonth)
                   ->whereYear('recall_date', $currentYear);

            if ($user && $user->hasRole('patient')) {
                $query->where('patient_id', $user->userable_id);
            }
            $monthlyRecallCount = $query->count();

            $recallsThisMonth = (clone $query)
                ->orderBy('recall_date', 'asc')
                ->take(5)
                ->get();

            $view->with(compact('monthlyRecallCount', 'recallsThisMonth'));
        });
    }
}