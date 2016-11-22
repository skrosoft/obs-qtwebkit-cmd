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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
            ->addOption(
                'startat',
                null,
                InputOption::VALUE_OPTIONAL,
                '¿Desde que numero quiere empezar a generar instancias?',
                1
            )
            ->addOption(
                'builddir',
                null,
                InputOption::VALUE_OPTIONAL,
                '¿Donde generar los archivos del build?',
                sys_get_temp_dir()
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $quantity = (int)$input->getOption('quantity');
        $startat = (int)$input->getOption('startat');
        $builddir = $input->getOption('builddir');

        $root_dir = $this->getContainer()->getParameter('kernel.root_dir');
        $base_dir = realpath($root_dir . '/../vendor/skrosoft/obs-qtwebkit');

        $fs = new Filesystem();

        foreach(range($startat, (($startat+$quantity)-1)) as $instance){

            $instance_builddir = "{$builddir}/obs-qtwebkit-{$instance}";

            $fs->remove($instance_builddir);

            $fs->mkdir($instance_builddir);

            $files = $this->getDirContents($base_dir);

            foreach($files as $file){

                $target_file = str_replace($base_dir, $instance_builddir, $file);

                echo "$file => $target_file";

                if (is_dir($file)){
                    $fs->mkdir($target_file);
                }else{

                    if ((new File($file))->getExtension() == 'twig'){

                        $target_file = str_replace('.twig', '', $target_file);

                        $content = $this->getContainer()->get('templating')->render($file, array(
                            'instance' => $instance
                        ));

                        file_put_contents($target_file, $content);

                    }else{
                        $fs->copy($file, $target_file);
                    }
                }
            }

            $process = new Process("make && make install");
            $process->setWorkingDirectory($instance_builddir);

            $process->run(function ($type, $buffer)use($process) {
                if (Process::ERR === $type) {
                    echo "ERR > {$buffer}\n";
                } else {
                    echo "OUT > {$buffer}\n";
                }
            });
        }
    }

    protected function getDirContents($dir, &$results = array()){
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                $results[] = $path;
                $this->getDirContents($path, $results);
            }
        }

        return $results;
    }
}