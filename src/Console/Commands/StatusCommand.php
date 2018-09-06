<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

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
    protected $description = 'Get information about supervisor.';

    public function handle(): void
    {
        $this->handleOptions();

        $data = collect($this->getApi()->getAllProcessInfo())->map(function (array $process) {
            return [
                $process['pid'],
                $process['group'],
                $process['name'],
                strtolower($process['statename']),
                $this->diffForHumans($process['start']),
                $process['logfile'],
            ];
        });

        $this->table(['PID', 'GROUP', 'NAME', 'STATE', 'UPTIME', 'LOG FILE'], $data);
    }


}
