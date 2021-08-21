<?php

namespace App\Command;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProductUpdateViewsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:product:update:views';
    /**
     * @var string
     */
    protected static string $defaultDescription = 'update product views';

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @param ProductRepository $productRepository
     * @param EntityManagerInterface $manager
     * @param string|null $name
     */
    public function __construct(ProductRepository $productRepository, EntityManagerInterface $manager, string $name = null)
    {
        $this->productRepository = $productRepository;
        parent::__construct($name);
        $this->manager = $manager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('amount',
                InputArgument::OPTIONAL,
                'Change the view value of all products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $amount = $input->getArgument('amount');
        $products = $this->productRepository->findAll();

        if ($amount) {
            foreach ($products as $product) {
                $product->setViews($amount);
                $this->manager->flush();
            }
        } else {
            $io->error('You must pass the amount argument.');
            die();
        }

        $this->manager->flush();
        $io->success('Done!');
        return 0;
    }
}
