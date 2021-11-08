<?php

namespace Exhum4n\Components\Console\Commands;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Exhum4n\Components\Providers\ServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class InitCmakeCommand extends Command
{
    protected $name = 'cmake:init';
    protected $description = 'Create a new component skeleton.';

    protected Inflector $inflector;
    private string $componentNameSingular;
    private string $componentNamePlural;

    private string $componentPath;

    protected ConsoleOutput $consoleOutput;

    private const CONSOLE_OPTION = '--component';


    public function __construct()
    {
        parent::__construct();

        $this->inflector = InflectorFactory::create()->build();
        $this->consoleOutput = new ConsoleOutput();
    }

    public function handle(): void
    {
        $this->setComponentName();
        $this->makeComponentDir();

        $this->createController();
        $this->createModel();
        $this->createSeeder();
        $this->createFactory();
        $this->createRepository();
        $this->createServiceProvider();
        $this->createProvider();

        $this->info("\nComponent created successfully.");
    }


    private function setComponentName(): void
    {
        $answer = $this->ask('Set the name of the component dir');
        if (empty($answer)) {
            $this->alert('Name can`t be empty!');
            $this->setComponentName();

            return;
        }

        $this->componentNameSingular = $this->inflector->singularize($answer);
        $this->componentNameSingular = $this->inflector->classify($this->componentNameSingular);
        $this->componentNamePlural = $this->inflector->pluralize($answer);
        $this->componentNamePlural = $this->inflector->classify($this->componentNamePlural);

        $this->componentPath = "components/$this->componentNamePlural";
    }

    private function makeComponentDir(): void
    {
        if (file_exists($this->componentPath)) {
            return;
        }

        $mkdir = function () {
            if (!mkdir($this->componentPath, 0755, true)
                && !is_dir($this->componentPath)) {
                return;
            }

            $this->info("Category $this->componentPath created successfully.");
        };

        $this->checkCreationOrFail($mkdir);
    }

    private function checkCreationOrFail(callable $func): void
    {
        try {
            $func();
        } catch (Throwable $exception) {
            $this->alert(sprintf('Directory "%s" was not created', $this->componentPath));
            exit(1);
        }
    }

    private function makeClassName(string $type): string
    {
        if ($type === 'model') {
            return $this->componentNameSingular;
        }

        return "{$this->componentNameSingular}{$this->inflector->classify($type)}";
    }


    private function createController(): void
    {
        $classname = $this->makeClassName('controller');

        if ($this->confirm("Would you like to create a $classname?")) {
            Artisan::call(ControllerCmakeCommand::class, [
                'name' => $classname,
                self::CONSOLE_OPTION => $this->componentNamePlural
            ], $this->consoleOutput);
        }
    }

    private function createModel(): void
    {
        $classname = $this->makeClassName('model');

        if ($this->confirm("Would you like to create a $classname model?")) {
            $this->call(ModelCmakeCommand::class, [
                'name' => $classname,
                self::CONSOLE_OPTION => $this->componentNamePlural
            ]);
        }
    }

    private function createSeeder(): void
    {
        $classname = $this->makeClassName('seeder');

        if ($this->confirm("Would you like to create a $classname?")) {
            $this->call(SeederCmakeCommand::class, [
                'name' => $classname,
                self::CONSOLE_OPTION => $this->componentNamePlural
            ]);
        }
    }

    private function createFactory(): void
    {
        $classname = $this->makeClassName('factory');

        if ($this->confirm("Would you like to create a $classname?")) {
            $this->call(FactoryCmakeCommand::class, [
                'name' => $classname,
                self::CONSOLE_OPTION => $this->componentNamePlural
            ]);
        }
    }

    private function createRepository(): void
    {
        $classname = $this->makeClassName('repository');

        if ($this->confirm("Would you like to create a $classname?")) {
            $this->call(RepositoryCmakeCommand::class, [
                'name' => $classname,
                self::CONSOLE_OPTION => $this->componentNamePlural
            ]);
        }
    }

    private function createServiceProvider(): void
    {
        $classname = $this->makeClassName('service provider');

        if ($this->confirm("Would you like to create a $classname?")) {
            $this->call(ProviderCmakeCommand::class, [
                'name' => $classname,
                self::CONSOLE_OPTION => $this->componentNamePlural
            ]);
        }
    }


    protected function getOptions(): array
    {
        return [
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ]
        ];
    }
}
