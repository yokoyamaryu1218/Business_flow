<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Task;

class NavigationComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('navigation-menu', function ($view) {
            $works = Task::where('is_visible', 1)->get();
            $work_list = [];
            foreach ($works as $work) {
                array_push($work_list, $work);
            }

            $view->with('work_list', $work_list);
        });
    }
}
