<?php

namespace GarbarczykFilip\LaravelCommands;

use Illuminate\Support\ServiceProvider;
use GarbarczykFilip\LaravelCommands\Console\Commands\Make\Resource\CustomResourceMakeCommand;

class LaravelCommandsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            CustomResourceMakeCommand::class,
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Console/Commands/Make/Resource/stubs/resource.stub' => base_path('stubs/resource.stub'),
            __DIR__ . '/Console/Commands/Make/Resource/stubs/resource-collection.stub' => base_path('stubs/resource-collection.stub'),
        ], 'stubs');
    }
}
