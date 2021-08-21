<?php

namespace App\Tests\Services;

use App\Entity\Product;
use App\Services\GetTopProductService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetTopProductServiceTest extends KernelTestCase
{

    public function testGetTopFromProduct()
    {
        self::bootKernel();
        $container = self::$container;

        /**
         * @var GetTopProductService $getTopProductService
         */
        $getTopProductService = $container->get(GetTopProductService::class);

        /**
         * @var EntityManagerInterface $em
         */
        $em = $container->get('doctrine')->getManager();

        $product = new Product();
        $product->setName('product-test');
        $product->setModel('model-test');
        $product->setBrand('brand-test');
        $product->setPrice(10000);
        $product->setBriefDescription('short-dec-test');
        $product->setDescription('description-test');
        $product->setRate(4);
        $product->setImage('2.jpg');
        $product->setSize(10);
        $product->setColor('red');
        $product->setDiscount(null);
        $product->setViews(9876);
        $product->setQuantity(1);


        $em->persist($product);
        $em->flush();

        // most expensive test
        /**
         * @var Product[] $mostExpensiveProducts
         */
        $mostExpensiveProducts = $getTopProductService->getTopFromProduct('p.price');
        self::assertNotEmpty($mostExpensiveProducts);
        self::assertNotNull($mostExpensiveProducts);
        self::assertCount(3, $mostExpensiveProducts);
        self::assertNotCount(4, $mostExpensiveProducts);
        self::assertNotCount(2, $mostExpensiveProducts);

        $product1 = $mostExpensiveProducts[0];
        self::assertEquals(10000, $product1->getPrice());

        // most viewed test
        /**
         * @var Product[] $mostViewedProducts
         */
        $mostViewedProducts = $getTopProductService->getTopFromProduct('p.views');
        self::assertNotEmpty($mostViewedProducts);
        self::assertNotNull($mostViewedProducts);
        self::assertCount(3, $mostViewedProducts);
        self::assertNotCount(4, $mostViewedProducts);
        self::assertNotCount(2, $mostViewedProducts);

        $product1 = $mostViewedProducts[0];
        self::assertEquals(9876, $product1->getViews());


        // least quantity test
        /**
         * @var Product[] $leastQuantityProducts
         */
        $leastQuantityProducts = $getTopProductService->getTopFromProduct('p.quantity', 'ASC', 4);
        self::assertNotEmpty($leastQuantityProducts);
        self::assertNotNull($leastQuantityProducts);
        self::assertCount(4, $leastQuantityProducts);
        self::assertNotCount(5, $leastQuantityProducts);
        self::assertNotCount(3, $leastQuantityProducts);

        $product1 = $leastQuantityProducts[0];
        self::assertEquals(1, $product1->getQuantity());


    }
}
