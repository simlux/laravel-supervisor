<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

/**
 * Class InfoCommand
 *
 * @package InspectYourWeb\Console\Commands\Supervisor
 */
class InfoCommand extends AbstractSupervisorCommand
{
    /**
     * @var string
     */
    protected $signature = 'supervisor:info {--host=} 
                                            {--port=}
                                            {--user=}
                                            {--password=}';

    /**
     * @var string
     */
    protected $description = 'Get information about supervisor.';

    public function handle(): void
    {
        $this->handleOptions();

        try {
            $this->info('Supervisor Version: ' . $this->getApi()->getSupervisorVersion());
            $this->info('API Version:        ' . $this->getApi()->getApiVersion());
            $this->info('Identification:     ' . $this->getApi()->getIdentification());
            $this->info('State:              ' . $this->getApi()->getState()['statename']);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
