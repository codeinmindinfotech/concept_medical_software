<?php

namespace App\Providers;

use App\Helpers\AuthHelper;
use App\Models\Company;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Recall;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Models\Patient;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot() {
        Blade::if('usercan', function ($permission) {
            return user_can($permission);
        });

        View::composer('backend.theme.header', function ($view) {
            if ((AuthHelper::isRole('clinic') || AuthHelper::isRole('doctor') || AuthHelper::isRole('patient') || AuthHelper::isRole('manager')) && session('company_name')) {
                $companyName = session('company_name');

                if ($companyName) {
                    $company = Company::where('name', $companyName)->first(); // use code instead of ID
                    if ($company) {
                        switchToCompanyDatabase($company);
                    }
                }
            }
            $user = AuthHelper::user();

            // === Recall Data ===
            $currentMonth = now()->month;
            $currentYear = now()->year;
        
            $recallQuery = \App\Models\Recall::whereMonth('recall_date', $currentMonth)
                ->whereYear('recall_date', $currentYear);
        
            if (AuthHelper::isRole('patient')) {
                $user = AuthHelper::user();
                $recallQuery->where('patient_id', $user->id);
            }
        
            $monthlyRecallCount = $recallQuery->count();
        
            $recallsThisMonth = (clone $recallQuery)
                ->orderBy('recall_date', 'asc')
                ->take(5)
                ->get();
        
            // === Task Data ===
            $taskQuery = \App\Models\Task::query();
        
            if ($user) {
                $taskQuery->where(function ($q) use ($user) {
                    $q->where('task_owner_id', $user->id)
                        ->orWhere('task_creator_id', $user->id);
                });
            }
        
            $taskQuery->whereDate('start_date', '>=', now())
                ->orderBy('start_date', 'asc');
        
            $upcomingTasks = (clone $taskQuery)
                ->take(5)
                ->get();
        
            $taskCount = (clone $taskQuery)->count();
        
            $view->with(compact(
                'monthlyRecallCount',
                'recallsThisMonth',
                'upcomingTasks',
                'taskCount'
            ));
        });
        
    }
}