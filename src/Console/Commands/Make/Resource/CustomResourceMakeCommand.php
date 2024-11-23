<?php

namespace GarbarczykFilip\LaravelCommands\Console\Commands\Make\Resource;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Illuminate\Support\Str;

final class CustomResourceMakeCommand extends ResourceMakeCommand
{
    protected string $suffix = '';

    protected string $model = '';

    public function handle(): void
    {
        $this->suffix = 'Resource';
        $this->model = '\\App\\Models\\' . $this->ask('Specify resource class');

        if (empty($this->model)) {
            $this->error('Model must be specified.');
            return;
        }

        if (!class_exists($this->model)) {
            $this->error(sprintf('Class "%s" does not exist.', $this->model));
            return;
        }

        parent::handle();
    }

    protected function getStub(): string
    {
        return $this->collection()
            ? __DIR__ . '/stubs/resource-collection.stub'
            : __DIR__ . '/stubs/resource.stub';
    }

    protected function qualifyClass($name): string
    {
        if (!empty($this->suffix) && !Str::contains($name, $this->suffix)) {
            $name .= $this->suffix;
        }

        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name
        );
    }

    protected function replaceModel(string &$stub): static
    {
        $stub = str_replace(['DummyModel', '{{ model }}', '{{model}}'], $this->model, $stub);

        return $this;
    }

    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this
            ->replaceNamespace($stub, $name)
            ->replaceModel($stub)
            ->replaceClass($stub, $name);
    }
}
