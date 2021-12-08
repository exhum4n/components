<?php

namespace Exhum4n\Components\Console\Commands;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class EntityCmakeCommand extends Command
{
    protected const ARG_NAME = 'name';
    protected const ARG_COMPONENT = 'component';
    protected const OPT_MODEL = '--model';
    protected const OPT_FORCE = '--force';
    protected const SELF_OPT_FORCE = 'force';
    protected const TYPE_MODEL = 'model';

    /**
     * @var string
     */
    protected $name = 'cmake:entity';

    /**
     * @var string
     */
    protected $description = 'Create a new entity';

    protected Inflector $inflector;
    protected string $componentNameSingular;
    protected string $componentNamePlural;
    protected string $componentPath;
    protected ConsoleOutput $consoleOutput;
    protected string $modelName;

    public function __construct()
    {
        parent::__construct();

        $this->inflector = InflectorFactory::create()->build();
        $this->consoleOutput = new ConsoleOutput();
    }

    public function handle(): void
    {
        $this->setComponentName();
        $this->setComponentPath();
        $this->makeComponentDir();

        $this->createModel();
        $this->createController();
        $this->createSeeder();
        $this->createFactory();
        $this->createRepository();
        $this->createProvider();

        $this->info("\nComponent created successfully.");
    }

    protected function setComponentName(): void
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
    }

    protected function setComponentPath(): void
    {
        $this->componentPath = $this->componentPath = "components/$this->componentNamePlural";
    }

    protected function makeComponentDir(): void
    {
        if (file_exists($this->componentPath)) {
            return;
        }

        $mkdir = function () {
            if ($this->dirNotCreated()) {
                return;
            }

            $this->info("Category $this->componentPath created successfully.");
        };

        $this->checkCreationOrFail($mkdir);
    }

    protected function dirNotCreated(): bool
    {
        return !mkdir($this->componentPath, 0755, true) && !is_dir($this->componentPath);
    }

    protected function makeClassName(string $type): string
    {
        if ($type === static::TYPE_MODEL) {
            return $this->componentNameSingular;
        }

        return "{$this->componentNameSingular}{$this->inflector->classify($type)}";
    }

    protected function createModel(): void
    {
        $classname = $this->makeClassName(static::TYPE_MODEL);

        $this->modelName = $classname;

        if ($this->confirm("Would you like to create a $classname model?")) {
            $this->call(ModelCmakeCommand::class, [
                static::ARG_NAME => $classname,
                static::ARG_COMPONENT => $this->componentNamePlural,
                static::OPT_FORCE => (bool) $this->option(static::SELF_OPT_FORCE)
            ]);
        }
    }

    protected function createController(): void
    {
        $classname = $this->makeClassName('controller');

        if ($this->confirm("Would you like to create a $classname?")) {
            Artisan::call(ControllerCmakeCommand::class, [
                static::ARG_NAME => $classname,
                static::ARG_COMPONENT => $this->componentNamePlural,
                static::OPT_MODEL => $this->modelName,
                static::OPT_FORCE => (bool) $this->option(static::SELF_OPT_FORCE)
            ], $this->consoleOutput);
        }
    }

    protected function createSeeder(): void
    {
        $classname = $this->makeClassName('seeder');

        if ($this->confirm("Would you like to create a $classname?")) {
            $this->call(SeederCmakeCommand::class, [
                static::ARG_NAME => $classname,
                static::ARG_COMPONENT => $this->componentNamePlural,
                static::OPT_FORCE => (bool) $this->option(static::SELF_OPT_FORCE)
            ]);
        }
    }

    protected function createFactory(): void
    {
        $classname = $this->makeClassName('factory');

        if ($this->confirm("Would you like to create a $classname?")) {
            $this->call(FactoryCmakeCommand::class, [
                static::ARG_NAME => $classname,
                static::ARG_COMPONENT => $this->componentNamePlural,
                static::OPT_MODEL => $this->modelName,
                static::OPT_FORCE => (bool) $this->option(static::SELF_OPT_FORCE)
            ]);
        }
    }

    protected function createRepository(): void
    {
        $classname = $this->makeClassName('repository');

        if ($this->confirm("Would you like to create a $classname?")) {
            Artisan::call(RepositoryCmakeCommand::class, [
                static::ARG_NAME => $classname,
                static::ARG_COMPONENT => $this->componentNamePlural,
                static::OPT_MODEL => $this->modelName,
                static::OPT_FORCE => (bool) $this->option(static::SELF_OPT_FORCE)
            ], $this->consoleOutput);
        }
    }

    protected function createProvider(): void
    {
        $classname = $this->makeClassName('service provider');

        if ($this->confirm("Would you like to create a $classname?")) {
            $this->call(ProviderCmakeCommand::class, [
                static::ARG_NAME => $classname,
                static::ARG_COMPONENT => $this->componentNamePlural,
                static::OPT_FORCE => (bool) $this->option(static::SELF_OPT_FORCE)
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

    protected function checkCreationOrFail(callable $func): void
    {
        try {
            $func();
        } catch (Throwable $exception) {
            $this->alert(sprintf('Directory "%s" was not created', $this->componentPath));
            exit(Command::FAILURE);
        }
    }
}
