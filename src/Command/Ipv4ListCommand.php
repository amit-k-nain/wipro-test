<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Ipv4ListCommand extends Command
{
    protected static $defaultName = 'ipv4_list';
    protected static $defaultDescription = 'Command to generate N number of unique IPv4 list for given IP range';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('initial', InputArgument::REQUIRED, 'Intial IP Range')
            ->addArgument('last', InputArgument::REQUIRED, 'End point IP Range')
            ->addArgument('num', InputArgument::REQUIRED, 'Number of IPv4')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $initial = $input->getArgument('initial');
        $last = $input->getArgument('last');
        $num = $input->getArgument('num');

        if ($initial) {
            $io->note(sprintf('You entered Intial IP Range: %s', $initial));
        }
        if ($last) {
            $io->note(sprintf('You entered End point IP Range: %s', $last));
        }
        if ($num) {
            $io->note(sprintf('You required Number of IPv4: %s', $num));
        }
        
        for ($a = 0; $a <= $num; $a++) {
            $a = rand($initial,$last);
            $ip = "127.0.0.".$a;
            echo $ip;
            // $io->note(sprintf('You IP Address is : %s', $ip));
            // $io->writeln($ip);
        }
        
        $io->success("Done !");
        return 0;
    }
}
