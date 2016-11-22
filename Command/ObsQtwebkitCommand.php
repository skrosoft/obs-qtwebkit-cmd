<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 22/11/2016
 * Time: 12:07
 */
namespace Onaxis\Bundle\ObsQtwebkitCmdBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ObsQtwebkitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('obsqtwebkit:generate')
            ->setDescription('Generate obs-qtwebkit instances')
            ->addOption(
                'quantity',
                null,
                InputOption::VALUE_OPTIONAL,
                '¿Cuantas instancias deseas generar?',
                10
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $quantity = $input->getOption('quantity');

        $output->writeln("HOLAAAAA: " . $quantity);
    }
}