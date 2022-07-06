<?php

namespace Openphp\Console\Scheduling\Commands;

use Openphp\Console\Command;
use Openphp\Console\Console;
use Openphp\Console\Scheduling\Schedule;

class ScheduleListCommand  extends Command
{
    /**
     * @var string
     */
    protected $name = 'schedule:list';
    /**
     * @var string
     */
    protected $description = 'List the scheduled commands';

    /**
     * @var Schedule
     */
    public static $schedule;

    /**
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $schedule = Console::$schedule;
        foreach ($schedule->events as $event) {
            $rows[] = [
                $event->command,
                $event->expression,
                $event->description,
                $event->getNextRunDate()->format('Y-m-d H:i:s P'),

            ];
        }
        $this->table([
            'Command',
            'Interval',
            'Description',
            'NextDue',
        ], $rows ?? []);
    }
}
