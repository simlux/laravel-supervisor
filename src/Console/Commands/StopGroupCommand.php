<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

use Supervisor\ApiException;

/**
 * Class StopCommand
 *
 * @package InspectYourWeb\Console\Commands\Supervisor
 */
class StopGroupCommand extends AbstractSupervisorCommand
{
    const ARGUMENT_GROUP = 'group';

    /**
     * @var string
     */
    protected $signature = 'supervisor:stop {group} 
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

        $group = $this->argument(self::ARGUMENT_GROUP);

        try {
            $this->info(sprintf('Stopping group %s', $group));
            $this->getApi()->stopProcessGroup($group);
        } catch (ApiException $e) {
            $this->error($e->getMessage());
        }
    }
}