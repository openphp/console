<?php

namespace Openphp\Console;


use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Command extends SymfonyCommand
{
    use HasParameters, InteractsWithIO, CallsCommands;

    /**
     * The console command name.
     * @var string
     */
    protected $name;

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     * @var string
     */
    protected $description;

    /**
     * The console command help text.
     * @var string
     */
    protected $help;

    /**
     * Command constructor.
     * @return  void
     */
    public function __construct()
    {
        if (isset($this->signature)) {
            $this->configureUsingFluentDefinition();
        } else {
            parent::__construct($this->name);
        }
        $this->setDescription((string)$this->description);
        $this->setHelp((string)$this->help);
        $this->setHidden($this->isHidden());
        if (!isset($this->signature)) {
            $this->specifyParameters();
        }
    }

    /**
     * Configure the console command using a fluent definition.
     * @return void
     */
    protected function configureUsingFluentDefinition()
    {
        [$name, $arguments, $options] = CommandParser::parse($this->signature);
        parent::__construct($this->name = $name);
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->output = new OutputStyle($input, $output);
        return parent::run($this->input = $input, $this->output);
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $method = method_exists($this, 'handle') ? 'handle' : '__invoke';
        return (int)$this->$method();
    }


    /**
     * @param $command
     * @return mixed|SymfonyCommand
     */
    protected function resolveCommand($command)
    {
        if (!class_exists($command)) {
            return $this->getApplication()->find($command);
        }
        $command = new $command;
        if ($command instanceof SymfonyCommand) {
            $command->setApplication($this->getApplication());
        }
        return $command;
    }

}