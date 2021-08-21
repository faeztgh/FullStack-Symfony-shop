<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AboutController
 * @package App\Controller
 * @Route("/about")
 */
class AboutController extends AbstractController
{
    /**
     * @Route("/", name="about")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $jsonData = array();
        $idx = 0;
        foreach ($products as $product) {
            $temp = array(
                'id' => $product->getId(),
                'name' => $product->getName(),
                'address' => $product->getBrand(),
            );
            $jsonData[$idx++] = $temp;
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($jsonData);
        } else {

            return $this->render('about/index.html.twig', [
                'controller_name' => 'AboutController',
            ]);
        }
    }


}
