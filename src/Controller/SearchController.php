<?php

namespace App\Controller;

use App\Services\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchController extends AbstractController
{
    /**
     * @var TranslatorInterface $translator
     */
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {

        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param SearchService $searchService
     * @return Response
     * @Route("/search", name="search")
     */
    public function searchProducts(Request $request, SearchService $searchService): Response
    {
        $query = $request->query->get('query');
        $products = $searchService->searchProducts($query);

        if ($products == null) {
            $this->addFlash("error", $this->translator->trans('app.search.controller.No Product Found'));

            return $this->render('search/index.html.twig', [
                "query" => $query,
                "products" => null,
            ]);
        }

        if (trim($query) == '') {
            $this->addFlash("error", $this->translator->trans('app.search.controller.please Enter Something'));
            return $this->render('search/index.html.twig', [
                "query" => $query,
                "products" => null,
            ]);
        }

        return $this->render('search/index.html.twig', [
            "query" => $query,
            "products" => $products,
        ]);
    }
}
