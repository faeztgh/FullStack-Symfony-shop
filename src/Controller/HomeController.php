<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Entity\Product;
use App\Services\GetTopProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route("/")
 *
 */
class HomeController extends AbstractController
{
    /**
     * @param GetTopProductService $getTopProductService
     * @return Response
     * @Route("/", name="home")
     */
    public function index(GetTopProductService $getTopProductService): Response
    {
        $em = $this->getDoctrine()->getManager();
        $discounts = $em->getRepository(Discount::class)->findAll();
        $maxNum = 0;
        $maxDiscount = null;
        foreach ($discounts as $discount) {
            if ($discount->getDiscountPercent() > $maxNum) {
                $maxNum = $discount->getDiscountPercent();
                $maxDiscount = $discount;
            }
        }

        $productRepo = $em->getRepository(Product::class);
        $allProducts = $productRepo->findAll();

        $randomProducts = [];
        for ($i = 0; $i < 10; $i++) {
            array_push($randomProducts, $allProducts[rand(0, count($allProducts) - 1)]);
        }

        // max prices
        /**
         * @var Product[] $mostExpensiveProducts
         */
        $mostExpensiveProducts = $getTopProductService->getTopFromProduct('p.price');
        /**
         * @var Product[] $mostViewedProducts
         */
        $mostViewedProducts = $getTopProductService->getTopFromProduct('p.views');
        /**
         * @var Product[] $leastQuantityProducts
         */
        $leastQuantityProducts = $getTopProductService->getTopFromProduct('p.quantity', 'ASC');

        // remove item if quantity is ZERO
        $maxRes = 3;
        foreach ($leastQuantityProducts as $key => $value) {
            if ($value->getQuantity() === 0) {
                $maxRes++;
                $leastQuantityProducts = $getTopProductService->getTopFromProduct('p.quantity', 'ASC', $maxRes);
                unset($leastQuantityProducts[$key]);
            }
        }

        $product = $em->getRepository(Product::class)
            ->findAllByDiscount($maxDiscount);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'top_discount_product' => $product[0],
            'max_discount' => $maxNum,
            'products' => $randomProducts,
            'luxProducts' => $mostExpensiveProducts,
            'mostViewedProducts' => $mostViewedProducts,
            'leastQuantityProducts' => $leastQuantityProducts
        ]);
    }


}
