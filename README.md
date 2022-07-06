## 内置Scheduling计划任务

## 特性

- 通过配置文件注册任务
- 支持秒级的定时任务粒度
- 使用symfony/process进行进程管理
- 使用dragonmantank/cron-expression进行解析cron表达式

## 全局变量

```
BASE_PATH 项目根目录,默认使用getcwd()进行获取
define("BASE_PATH", __DIR__);

CONSOLE_NAME,脚本入口文件，默认openphp
define("CONSOLE_NAME", 'openphp');
```

## 命令详解

```
schedule:init        项目初始化
schedule:list        计划任务列表
schedule:run         任务计划执行
schedule:work        启动调度工作者
```

## 初次使用

```
./vendor/bin/openphp-console schedule:init
```

会在你跟目录生成如下文件

```
├── app
│   └── OpenphpConsole.php
├── composer.json
├── openphp
```

WangConsole.php文件中进行注册计划任务

> 定义任务计划 和laravel中的任务计划使用方法差不多

```
<?php

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
}
```

入口文件

> 入口文件可以随意发挥，比如加载其他核心文件

```
#!/usr/bin/env php
<?php

require __DIR__."/vendor/autoload.php";

define("BASE_PATH", __DIR__);

(new \App\OpenphpConsole)->handle();
```

## 启动调度器，使用调度器时，只需将以下 `cron` 项目添加到服务器:

```
* * * * * php /path-to-your-project/openphp schedule:run >> /dev/null 2>&1
```
