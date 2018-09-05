<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

use Carbon\Carbon;

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

    /**
     * @param      $dateTime
     * @param null $now
     *
     * @return string
     */
    private function diffForHumans($dateTime, $now = null)
    {
        if (is_string($dateTime)) {
            $dateTime = Carbon::parse($dateTime);
        } elseif (is_int($dateTime)) {
            $dateTime = Carbon::createFromTimestamp($dateTime);
        } elseif ($dateTime instanceof \DateTime) {
            $dateTime = Carbon::parse($dateTime->format('Y-m-d H:i:s'));
        }

        if (is_null($now)) {
            $now = Carbon::now();
        }

        $seconds = $dateTime->diffInSeconds($now);

        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;

        $days = floor($hours / 24);
        $hours -= $days * 24;

        $parts = [];

        if ($days > 0) {
            $parts[] = $days . 'd';
        }

        if ($hours > 0) {
            $parts[] = $hours . 'h';
        }

        if ($minutes > 0) {
            $parts[] = $minutes . 'm';
        }

        if ($seconds > 0) {
            $parts[] = $seconds . 's';
        }

        return implode(' ', $parts);
    }
}
