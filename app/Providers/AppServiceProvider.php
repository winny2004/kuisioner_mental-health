<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Register global helper function
        if (!function_exists('getDASS21Label')) {
            function getDASS21Label($score, $type)
            {
                $labels = [
                    'depression' => [
                        [0, 9, 'Normal'],
                        [10, 13, 'Mild (Ringan)'],
                        [14, 20, 'Moderate (Sedang)'],
                        [21, 27, 'Severe (Berat)'],
                        [28, 42, 'Extremely Severe (Sangat Berat)'],
                    ],
                    'anxiety' => [
                        [0, 7, 'Normal'],
                        [8, 9, 'Mild (Ringan)'],
                        [10, 14, 'Moderate (Sedang)'],
                        [15, 19, 'Severe (Berat)'],
                        [20, 42, 'Extremely Severe (Sangat Berat)'],
                    ],
                    'stress' => [
                        [0, 14, 'Normal'],
                        [15, 18, 'Mild (Ringan)'],
                        [19, 25, 'Moderate (Sedang)'],
                        [26, 33, 'Severe (Berat)'],
                        [34, 42, 'Extremely Severe (Sangat Berat)'],
                    ],
                ];

                $ranges = $labels[$type] ?? [];
                foreach ($ranges as $range) {
                    if ($score >= $range[0] && $score <= $range[1]) {
                        return $range[2];
                    }
                }

                return 'Unknown';
            }
        }

        // Share function with all views
        view()->composer('*', function ($view) {
            $view->with('getDASS21Label', function ($score, $type) {
                return getDASS21Label($score, $type);
            });
        });
    }
}
