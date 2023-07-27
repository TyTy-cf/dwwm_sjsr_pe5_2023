<?php

namespace App\Command;

use App\Entity\Region;
use App\Service\HttpClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:region', // symfony console app:region
    description: 'API call to fetch all regions from the government API',
)]
class RegionCommand extends Command
{

    public function __construct(
        private HttpClientService $httpClientService,
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
//        $this
//            ->addArgument('anArgument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('anArgument');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }

        $response = $this->httpClientService
            ->getFrom('https://geo.api.gouv.fr/regions/');

        $arrayRegions = json_decode($response->getContent(), true);

        $io->writeln('Régions récupérées depuis l\'API...');

        foreach ($arrayRegions as $arrayRegion) {
            $region = (new Region())
                ->setName($arrayRegion['nom'])
                ->setCode($arrayRegion['code'])
            ;
            $this->em->persist($region);
        }
        $this->em->flush();

        $io->writeln('Régions correctement ajoutées en BDD :)');

        return Command::SUCCESS;
    }
}
