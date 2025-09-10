<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Recall;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
         View::composer('backend.theme.header', function ($view) {
            $user = Auth::user();

            // === Recall Data ===
            $currentMonth = now()->month;
            $currentYear = now()->year;

            $recallQuery = Recall::whereMonth('recall_date', $currentMonth)
                ->whereYear('recall_date', $currentYear);

            if ($user && $user->hasRole('patient')) {
                $recallQuery->where('patient_id', $user->userable_id);
            }

            $monthlyRecallCount = $recallQuery->count();

            $recallsThisMonth = (clone $recallQuery)
                ->orderBy('recall_date', 'asc')
                ->take(5)
                ->get();

            // === Task Data ===
            $taskQuery = Task::query();

            // Optional: filter tasks by owner or creator based on role
            if ($user) {
                if ($user->hasRole('patient')) {
                    $taskQuery->where('patient_id', $user->userable_id);
                } else {
                    $taskQuery->where(function ($q) use ($user) {
                        $q->where('task_owner_id', $user->id)
                        ->orWhere('task_creator_id', $user->id);
                    });
                }
            }
            $taskQuery->whereDate('start_date', '>=', now())
                    ->orderBy('start_date', 'asc');
            $upcomingTasks = (clone $taskQuery)
                ->take(5)
                ->get();

            $taskCount = (clone $taskQuery)->count();

            // Pass all data to the view
            $view->with(compact(
                'monthlyRecallCount',
                'recallsThisMonth',
                'upcomingTasks',
                'taskCount'
            ));
        });
    }

}