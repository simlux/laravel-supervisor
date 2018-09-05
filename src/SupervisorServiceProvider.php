<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor;

use Illuminate\Support\ServiceProvider;
use Simlux\LaravelSupervisor\Console\Commands\InfoCommand;

/**
 * Class SupervisorServiceProvider
 *
 * @package Simlux\Supervisor
 */
class SupervisorServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {

    }

    public function register()
    {
        $this->commands([
            InfoCommand::class,
        ]);
    }

}