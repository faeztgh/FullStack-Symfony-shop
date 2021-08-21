<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{

    /**
     * @Route("/", name="shop", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return Response
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        if (!$this->getUser()) {
            $this->redirectToRoute('app_login');
        }
        $price = $request->query->get('price');
        $price = $price == "" ? "ASC" : $price;
        $date = $request->query->get('date');
        $date = $date == "" ? "ASC" : $date;
        $color = $request->query->get('color');
        $rate = $request->query->get('rate');
        $size = $request->query->get('size');

        $tmp = [
            "color" => $color,
            "rate" => $rate,
            "size" => $size
        ];
        $filters = [];

        foreach ($tmp as $key => $val) {
            if (trim($val) != "") {
                $filters += [$key => $val];
            }
        }
        $products = $productRepository->findBy($filters,
            [
                "price" => $price,
                "createdAt" => $date
            ]);

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            "filter_price" => $price ,
            "filter_date" => $date ,
            "filter_color" => $color,
            "filter_rate" => $rate,
            "filter_size" => $size
        ]);
    }


    /**
     * @Route("/{id}", name="shop_show", methods={"GET"}, requirements={"id"="\d+"})
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        if (!$this->getUser()) {
            $this->redirectToRoute('app_login');
        }
        $em = $this->getDoctrine()->getManager();
        $product->setViews($product->getViews() + 1);
        $em->persist($product);
        $em->flush();

        return $this->render('shop/show.html.twig', [
            'product' => $product,
        ]);
    }


}
