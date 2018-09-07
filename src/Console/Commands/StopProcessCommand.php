<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

use Supervisor\ApiException;

/**
 * Class StopCommand
 *
 * @package InspectYourWeb\Console\Commands\Supervisor
 */
class StopProcessCommand extends AbstractSupervisorCommand
{
    const ARGUMENT_PROCESS = 'process';

    /**
     * @var string
     */
    protected $signature = 'supervisor:stop:process {process} 
                                                    {--host=}
                                                    {--port=}
                                                    {--user=}
                                                    {--password=}';

    /**
     * @var string
     */
    protected $description = 'Stops s supervisor process.';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->handleOptions();

        $group = $this->getGroupName($this->argument(self::ARGUMENT_PROCESS), true);
        try {
            $this->info(sprintf('Stopping %s', $group));
            $this->getApi()->stopProcess($group);
        } catch (ApiException $e) {
            $this->error($e->getMessage());
        }
    }
}