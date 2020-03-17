<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelVismaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerConfig();
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/visma.php',
            'visma'
        );

        $this->publishes([
            __DIR__ . '/../config/visma.php' => config_path('visma.php'),
        ], 'config');
    }
}
