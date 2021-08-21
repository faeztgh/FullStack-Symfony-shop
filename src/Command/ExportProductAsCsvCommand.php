<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

class ExportProductAsCsvCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:product:export:csv';
    /**
     * @var string
     */
    protected static string $defaultDescription = 'Export CSV file of selected table';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    private KernelInterface $appKernel;

    /**
     * @param KernelInterface $appKernel
     * @param EntityManagerInterface $manager
     * @param string|null $name
     */
    public function __construct(KernelInterface $appKernel, EntityManagerInterface $manager, string $name = null)
    {
        $this->appKernel = $appKernel;
        parent::__construct($name);
        $this->manager = $manager;
    }


    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $products = $this->manager->getRepository('App:Product')->findAll();

        if (count($products) > 0) {
            $exportDir = $this->appKernel->getProjectDir() . '/exports/csv/';
            $csvPath = $exportDir . 'product.csv';
            $dateTime = new \DateTime();
            try {
                if (!file_exists($exportDir)) {
                    mkdir($exportDir, 0777, true);
                }

                $csvh = fopen($csvPath, 'w+');
                $d = ','; // this is the default but i like to be explicit
                $e = '"'; // this is the default but i like to be explicit
                fputcsv($csvh, [
                    'id',
                    'name',
                    'brand',
                    'model',
                    'price',
                    'color',
                    'size',
                    'briefDescription',
                    'description',
                    'rate',
                    'image',
                    'discount',
                    'views',
                    'quantity',
                    'createdAt',
                    'updatedAt',
                    'createdUser',
                    'updatedUser'
                ]);
                foreach ($products as $product) {
                    $data = [
                        $product->getId(),
                        $product->getName(),
                        $product->getBrand(),
                        $product->getModel(),
                        $product->getPrice(),
                        $product->getColor(),
                        $product->getSize(),
                        $product->getBriefDescription(),
                        $product->getDescription(),
                        $product->getRate(),
                        $product->getImage(),
                        $product->getDiscount()->getName(),
                        $product->getViews(),
                        $product->getQuantity(),
                        date_format($product->getCreatedAt(), 'd/m/y'),
                        date_format($product->getUpdatedAt(), 'd/m/y'),
                        $product->getCreatedUser(),
                        $product->getUpdatedUser(),
                    ];
                    fputcsv($csvh, $data);
                }

                fclose($csvh);
            } catch (Exception $exception) {
                $io->error($exception->getMessage());
                die();
            }

        } else {
            $io->error('You must pass the Record Name argument.');
            die();
        }

        $io->success('Your export file created and located at:' . $csvPath);
        return 0;
    }
}
