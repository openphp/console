<?php

namespace Openphp\Console\Scheduling\Commands;

use Openphp\Console\Command;

class ScheduleInitCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'schedule:init';

    /**
     * @var string
     */
    protected $description = 'schedule project init';


    /**
     * @return void
     */
    public function handle()
    {
        $arr                               = json_decode(file_get_contents('./composer.json'), true);
        $arr['autoload']['psr-4']['App\\'] = "app/";
        file_put_contents('./composer.json', json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->initfile();
        @exec('composer dump-autoload');
        $this->info('init success');
    }

    /**
     * @return void
     */
    protected function initfile()
    {
        $php = '<?php

namespace App;

use Openphp\Console\Console;
use Openphp\Console\Scheduling\Schedule;


class OpenphpConsole extends Console
{
    /**
     * 定义任务计划
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command("schedule:list")->everyMinute();
        $schedule->exec("sleep 5")->everyMinute();  
    }

    /**
     *  注册命令行
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__."/Commands");
    }
}';
        if (!is_dir('./app')){
            mkdir('./app');
        }
        file_put_contents('./app/OpenphpConsole.php', $php);


        $php = '#!/usr/bin/env php
<?php

require __DIR__."/vendor/autoload.php";

define("BASE_PATH", __DIR__);

(new \App\OpenphpConsole)->handle();

';
        file_put_contents('./openphp', $php);
    }

}