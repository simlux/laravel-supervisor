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
    const OPTION_NAME = 'name';

    /**
     * @var string
     */
    protected $signature = 'supervisor:status {--name=}
                                              {--host=} 
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

        if ($this->option(self::OPTION_NAME)) {
            try {

                dd($this->getApi()->getProcessInfo($this->option(self::OPTION_NAME)));

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
            } catch (ApiException $e) {
                $this->error($e->getMessage());
            }
        }

        try {
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
        } catch (ApiException $e) {
            $this->error($e->getMessage());
        }
    }


}
