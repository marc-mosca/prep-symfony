<?php

namespace App\Command;

use App\Command\Tools\UserImporter;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:import',
    description: "Importe les donnees de l'ancienne base de donnees vers la nouvelle",
)]
class ImportCommand extends Command
{

    public function __construct(
        private readonly UserImporter $userImporter,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('truncate', null, InputOption::VALUE_NONE, 'Vide la table avant import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->userImporter->import($io, truncate: $input->getOption("truncate"));

        return Command::SUCCESS;
    }
}
