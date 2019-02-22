<?php

namespace Dbt\ModelFactory;

use Illuminate\Support\ServiceProvider;

class ModelFactoryProvider extends ServiceProvider
{
    public function boot (): void
    {
        $this->publishes([
            __DIR__.'/model-factory.php' => config_path('model-factory.php'),
        ]);

        $this->registerFactories();
    }

    private function registerFactories (): void
    {
        $fqcns = $this->app->make('config')->get('model-factory.classes');

        if ($fqcns) {
            foreach ($fqcns as $fqcn) {
                $this->app->make($fqcn)->register();
            }
        }
    }
}
