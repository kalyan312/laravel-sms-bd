<?php

namespace Khbd\LaravelSmsBD\Console;

use Illuminate\Console\GeneratorCommand;

class MakeGatewayCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:gateway';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Gateway class for Laravel SMS';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Gateway';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/gateway.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Gateways';
    }
}
