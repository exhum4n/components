<?php declare(strict_types=1);


namespace Exhum4n\Components\Console\Commands;


use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class RepositoryCmakeCommand extends GeneratorCommand
{
    protected $name = 'cmake:repository';
    protected $description = 'Create a new repository class';
    protected $type = 'Repository';


    protected function getStub(): string
    {
        return $this->option('model')
            ? $this->resolveStubPath('/stubs/component-repository.stub')
            : $this->resolveStubPath('/stubs/component-repository.plain.stub');
    }

    public function handle(): ?bool
    {
        if (is_null($this->input->getOption('component'))) {
            $this->error('--component flag required.');

            return false;
        }

        return parent::handle();
    }

    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return 'components/' . str_replace('\\', '/', $name) . '.php';
    }

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        $model = $this->option('model');

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    protected function replaceModel($stub, $model)
    {
        $modelClass = $this->parseModel($model);

        $replace = [
            '{{ namespacedModel }}' => $modelClass,
            '{{ model }}' => class_basename($modelClass)
        ];

        return str_replace(
            array_keys($replace), array_values($replace), $stub
        );
    }

    protected function parseModel($model): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    protected function rootNamespace(): string
    {
        return 'Components\\';
    }

    protected function qualifyModel(string $model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return $rootNamespace
            . $this->input->getOption('component') . '\\'
            . 'Models\\' . $model;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\' . $this->input->getOption('component') . '\Repository';
    }

    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the repository applies to.'],
            ['component', 'C', InputOption::VALUE_REQUIRED, 'Choose a component']
        ];
    }
}
