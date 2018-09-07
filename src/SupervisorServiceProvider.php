<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor;

use Illuminate\Support\ServiceProvider;
use Simlux\LaravelSupervisor\Console\Commands\InfoCommand;
use Simlux\LaravelSupervisor\Console\Commands\StatusCommand;
use Simlux\LaravelSupervisor\Console\Commands\StopCommand;

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
    protected $defer = false;

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InfoCommand::class,
                StatusCommand::class,
                StopCommand::class,
            ]);
        }
    }

    public function register()
    {

    }

}