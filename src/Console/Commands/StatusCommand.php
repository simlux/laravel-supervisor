<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

use Supervisor\ApiException;

/**
 * Class StatusCommand
 *
 * @package InspectYourWeb\Console\Commands\Supervisor
 */
class StatusCommand extends AbstractSupervisorCommand
{
    /**
     * @var string
     */
    protected $signature = 'supervisor:status {--host=} 
                                              {--port=}
                                              {--user=}
                                              {--password=}';

    /**
     * @var string
     */
    protected $description = 'Get status from supervisor processes.';

    public function handle(): void
    {
        $this->handleOptions();

        try {
            $data = $this->getProcessList()->map(function($process) {
                return [
                    $process['pid'],
                    $process['group'],
                    $process['name'],
                    strtolower($process['statename']),
                    $this->diffForHumans($process['start']),
                    $process['logfile'],
                ];
            })->toArray();
            $this->table(['PID', 'GROUP', 'NAME', 'STATE', 'UPTIME', 'LOG FILE'], $data);
        } catch (ApiException $e) {
            $this->error($e->getMessage());
        }
    }
}