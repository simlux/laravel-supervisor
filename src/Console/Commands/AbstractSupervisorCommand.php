<?php declare(strict_types=1);

namespace Simlux\LaravelSupervisor\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Supervisor\Api;
use Carbon\Carbon;
use Supervisor\ApiException;

/**
 * Class AbstractSupervisorCommand
 *
 * @package Simlux\LaravelSupervisor\Console\Commands
 */
abstract class AbstractSupervisorCommand extends Command
{
    const OPTION_HOST     = 'host';
    const OPTION_PORT     = 'port';
    const OPTION_USER     = 'user';
    const OPTION_PASSWORD = 'password';

    /**
     * @var string
     */
    protected $signature = 'supervisor {--host=} 
                                       {--port=}
                                       {--user=}
                                       {--password=}';
    /**
     * @var string
     */
    protected $description = '';
    /**
     * @var Api
     */
    private $api;
    /**
     * @var string
     */
    protected $host = '127.0.0.1';
    /**
     * @var int
     */
    protected $port = 9001;
    /**
     * @var string
     */
    protected $username = '';
    /**
     * @var string
     */
    protected $password = '';

    /**
     * @return Api
     */
    protected function getApi(): Api
    {
        if (is_null($this->api)) {
            $this->api = new Api($this->host, $this->port, $this->username, $this->password);
        }

        return $this->api;
    }

    protected function handleOptions(): void
    {
        if ($this->option(self::OPTION_HOST)) {
            $this->host = $this->option(self::OPTION_HOST);
        }

        if ($this->option(self::OPTION_PORT)) {
            $this->port = $this->option(self::OPTION_PORT);
        }

        if ($this->option(self::OPTION_USER)) {
            $this->username = $this->option(self::OPTION_USER);
        }

        if ($this->option(self::OPTION_PASSWORD)) {
            $this->password = $this->option(self::OPTION_PASSWORD);
        }
    }

    /**
     * @return Collection
     */
    protected function getProcessList(): Collection
    {
        try {
            return collect($this->getApi()->getAllProcessInfo())->map(function (array $process) {
                return [
                    'pid'       => $process['pid'],
                    'group'     => $process['group'],
                    'name'      => $process['name'],
                    'statename' => strtolower($process['statename']),
                    'start'     => $process['start'],
                ];
            });
        } catch (ApiException $e) {
            $this->error($e->getMessage());

            return collect([]);
        }
    }

    /**
     * @param string $processName
     * @param bool   $processNameIncluded
     *
     * @return string
     */
    protected function getGroupName(string $processName, bool $processNameIncluded = false): string
    {
        $process = $this->getProcessList()->filter(function ($process) use ($processName) {
            return $process['name'] === $processName;
        })->first();

        $group = $process['group'] ?? '';

        return ($processNameIncluded) ? sprintf('%s:%s', $group, $processName) : $group;
    }

    /**
     * @param string|int|\DateTime $dateTime
     * @param null                 $now
     *
     * @return string
     *
     * @TODO: REFAC -> move it to datetime helper
     */
    protected function diffForHumans($dateTime, $now = null)
    {
        if (is_string($dateTime)) {
            $dateTime = Carbon::parse($dateTime);
        } else if (is_int($dateTime)) {
            $dateTime = Carbon::createFromTimestamp($dateTime);
        } else if ($dateTime instanceof \DateTime) {
            $dateTime = Carbon::parse($dateTime->format('Y-m-d H:i:s'));
        }

        if (is_null($now)) {
            $now = Carbon::now();
        }

        $seconds = $dateTime->diffInSeconds($now);

        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;

        $hours   = floor($minutes / 60);
        $minutes -= $hours * 60;

        $days  = floor($hours / 24);
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

    abstract public function handle(): void;
}