<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Recall;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Auth\CustomPasswordBrokerManager;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use App\Auth\CustomDatabaseTokenRepository; 
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use App\Models\Patient;
use Carbon\Carbon;


class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->extend(TokenRepositoryInterface::class, function ($app) {
            $config = $app['config']['auth.passwords.users']; // Or use dynamic logic if needed
            $key = $app['config']['app.key'];
    
            if (\Illuminate\Support\Str::startsWith($key, 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }
    
            return new CustomDatabaseTokenRepository(
                \DB::connection(),
                $app['hash'],
                $config['table'],
                $key,
                $config['expire'],
                $config['throttle'] ?? 0
            );
        });

        $this->app->extend('auth.password', function ($service, $app) {
            return new \App\Auth\CustomPasswordBrokerManager($app);
        });
    }
    
    public function boot()
    {
        Paginator::useBootstrapFive(); // 👈 This is the key line

        View::composer('backend.theme.tab-navigation', function ($view) {
            // Get today's date
            $today = Carbon::today();

            // Get patients with count of tasks, recalls, and appointments after today
            $patients = Patient::withCount([
                'tasks' => function($query) use ($today) {
                    $query->where('start_date', '>', $today);
                },
                'recall' => function($query) use ($today) {
                    $query->where('recall_date', '>', $today);
                },
                'appointments' => function($query) use ($today) {
                    $query->where('appointment_date', '>', $today);
                },
                'notes' => function($query) use ($today) {
                    $query->where('created_at', '>', $today);
                },
                'histories' => function($query) use ($today) {
                    $query->where('created_at', '>', $today);
                },
                'WaitingLists' => function($query) use ($today) {
                    $query->where('visit_date', '>', $today);
                },
                'FeeNoteList' => function($query) use ($today) {
                    $query->where('admission_date', '>', $today);
                }
                
            ])->find($view->patient->id);
            // Share the $patients variable with the view
            $view->with('patients', $patients);
        });

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
                if (has_role('patient')) {
                    $user = auth()->user();
                    $taskQuery->where('patient_id', $user->id);
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