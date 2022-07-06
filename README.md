## example

~~~
class Test extends \Openphp\Console\Command
{
    protected $name        = 'test1';
    protected $description = 'test-1';

    public function handle()
    {
        $this->info('test 1');
    }
}

class Test2 extends \Openphp\Console\Command
{
    protected $name        = 'test2';
    protected $description = 'test-2';

    public function handle()
    {
        $this->info('test 2');
    }
}

$application = new \Openphp\Console\Application();
//注册一个命令
//$application->resolve(Test::class);
//批量注册命令
$application->resolveCommands([Test::class, Test2::class]);
$application->run();
~~~
