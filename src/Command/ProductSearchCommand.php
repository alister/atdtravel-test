<?php

declare(strict_types=1);

namespace App\Command;

use App\Api\ProductsQuery;
use App\Repository\Api\ProductsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:product-search',
    description: 'Perform a search for AttractionTickets Products by Title',
)]
class ProductSearchCommand extends Command
{
    public function __construct(readonly private ProductsRepository $productsRepository)
    {
        parent::__construct();

    }

    protected function configure(): void
    {
        $this
            ->addOption('geo', null, InputOption::VALUE_REQUIRED, 'Option description', 'en', ['en', 'en-ie', 'de-de'])
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Limit per page') #, self::DEFAULT_LIMIT, [self::DEFAULT_LIMIT, 20, 50])
            ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Offset of results (not page)')  #, 0
            ->addArgument('title', InputArgument::REQUIRED, 'Title search')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $title = $input->getArgument('title');
        $geo = $input->getOption('geo');
        $limit = $input->getOption('limit');
        $offset = $input->getOption('offset');

        $optionsForIoList = [
            ['title' => "'$title'"],
            ['geo' => $geo],
            ['limit' => $limit ?? 'default (10)'],
            ['offset' => $offset ?? 'default (0)'],
        ];
        $io->definitionList(...$optionsForIoList);

        $productsQuery = new ProductsQuery($title, $geo, $limit, $offset);
        $results = $this->productsRepository->searchProducts($productsQuery);
        dump($results);

        return Command::SUCCESS;
    }
}
