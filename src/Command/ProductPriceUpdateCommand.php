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

class ProductPriceUpdateCommand extends Command
{
    protected static $defaultName = 'app:product:update:price';
    protected static string $defaultDescription = 'update product price by percent';
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * ProductPriceUpdateCommand constructor.
     * @param ProductRepository $productRepository
     * @param string|null $name
     * @param EntityManagerInterface $manager
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
                InputArgument::REQUIRED,
                'Increase or Decrease price by percentage')
            ->addOption('increase', 'i',
                InputOption::VALUE_NONE,
                'increase amount by percent')
            ->addOption('decrease', 'd',
                InputOption::VALUE_NONE,
                'decrease amount by percent')
            ->addOption('add', 'a',
                InputOption::VALUE_NONE,
                'increase amount')
            ->addOption('minus', 'm',
                InputOption::VALUE_NONE,
                'decrease amount');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $amount = $input->getArgument('amount');

        $decreaseOption = $input->getOption('decrease');
        $increaseOption = $input->getOption('increase');
        $addOption = $input->getOption('add');
        $minusOption = $input->getOption('minus');

        $products = $this->productRepository->findAll();

        if ($decreaseOption == false &&
            $increaseOption == false &&
            $addOption == false &&
            $minusOption == false
        ) {
            $io->error("You must pass -i, -d, -a or -m option");
            die();
        }

        $counter = 0;
        foreach ($products as $product) {
            $price = $product->getPrice();

            if ($decreaseOption) {
                $price = ceil($price - (($amount / 100) * $price) + 1);
            } else if ($increaseOption) {
                $price = ceil($price + (($amount / 100) * $price));
            } else if ($addOption) {
                $price = $price + $amount;
            } else if ($minusOption) {
                $price = $price - $amount;
            } else {
                die();
            }

            $product->setPrice($price);

            $counter++;
            if ($counter > 300) {
                $this->manager->flush();
                $counter = 0;
            }
        }
        $this->manager->flush();
        $io->success('Done!');
        return 0;
    }
}
