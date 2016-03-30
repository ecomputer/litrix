<?php

namespace Litrix\Bundle\AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class executeCommand extends Command
{
    private $app;
    public function __construct($app){
        $this->app=$app;
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setName('doCron:command')
            ->setDescription('Comando para ejecutar comandos')
            ->addArgument(
                'function'
            )
            ->addArgument(
                'optional',InputArgument::OPTIONAL
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $function = $input->getArgument('function');

        switch($function){
            case "send":
                break;

            case "getInfo":
                break;

            case "resend":
                break;

        }
    }
}