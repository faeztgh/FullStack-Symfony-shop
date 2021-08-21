<?php

namespace App\Tests\Services;

use App\Entity\Product;
use App\Services\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SearchServiceTest extends KernelTestCase
{

    public function testSearchProducts()
    {
        self::bootKernel();
        $container = self::$container;

        /**
         * @var SearchService $searchService
         */
        $searchService = $container->get(SearchService::class);
        /**
         * @var EntityManagerInterface $em
         */
        $em = $container->get('doctrine')->getManager();

        $product = new Product();
        $product->setName('product-test');
        $product->setModel('model-test');
        $product->setBrand('brand-test');
        $product->setPrice(100);
        $product->setBriefDescription('short-dec-test');
        $product->setDescription('description-test');
        $product->setRate(4);
        $product->setImage('2.jpg');
        $product->setSize(10);
        $product->setColor('red');
        $product->setDiscount(null);

        $em->persist($product);
        $em->flush();

        /**
         * @var Product[] $res
         */
        $res = $searchService->searchProducts("product-test");
        self::assertNotEmpty($res);

        $product1 = $res[0];
        self::assertEquals(100, $product1->getPrice());
    }
}
