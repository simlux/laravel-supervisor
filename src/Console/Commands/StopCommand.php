<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

use Supervisor\ApiException;

/**
 * Class StopCommand
 *
 * @package InspectYourWeb\Console\Commands\Supervisor
 */
class StopCommand extends AbstractSupervisorCommand
{
    const OPTION_PROCESS = 'process';

    /**
     * @var string
     */
    protected $signature = 'supervisor:stop {--process=} 
                                            {--host=}
                                            {--port=}
                                            {--user=}
                                            {--password=}';

    /**
     * @var string
     */
    protected $description = 'Stops s supervisor process.';

    public function handle(): void
    {
        $this->handleOptions();

        $group = $this->getGroupName($this->option(self::OPTION_PROCESS), true);
        try {
            $this->info(sprintf('Stopping %s', $group));
            $this->getApi()->stopProcess($group);
        } catch (ApiException $e) {
            $this->error($e->getMessage());
        }
    }
}