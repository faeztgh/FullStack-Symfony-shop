<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/wishlist")
 */
class WishlistController extends AbstractController
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
     * @Route("/", name="wishlist")
     * @throws Exception
     */
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());

        /**
         * @var Product[] $wishListItems
         */
        $wishListItems = $user->getWishlist();
        return $this->render('wishlist/index.html.twig', [
            'listItems' => $wishListItems,
        ]);
    }


    /**
     * @Route("/new", name="add_to_wishlist")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function addToWishlist(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());
        if ($request->isXmlHttpRequest()) {

            if ($this->getUser() != null) {

                $id = $request->request->get('id');
                $selectedProduct = $em->getRepository(Product::class)->find($id);

                // check if selected Item exist or not
                $isExistWishlistItem = false;

                foreach ($user->getWishlist() as $product) {
                    $selectedProduct->getId() === $product->getId() ? $isExistWishlistItem = true : $isExistWishlistItem = false;
                }

                $changedStatusColor = '';
                if (!$isExistWishlistItem) {
                    $user->addToWishlist($selectedProduct);
                    $changedStatusColor = "red";
                } else {
                    $user->removeFromWishlist($selectedProduct);
                    $changedStatusColor = "none";
                }
                $em->persist($user);
                $em->flush();
            }
            $wishlistItems = $user->getWishlist();

            return new JsonResponse([
                "status" => "success",
                "changedItemId" => $selectedProduct->getId(),
                "changedStatusColor" => $changedStatusColor,
                "wishlistItems" => count($wishlistItems)
            ]);
        } else {
            return new JsonResponse([
                "status" => $this->translator->trans('app.cart.controller.failed')
            ]);
        }
    }

    /**
     * @Route("/remove", name="remove_from_wishlist", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function removeFromWishlist(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $id = $request->request->get('id');
            $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());
            $selectedProduct = $em->getRepository(Product::class)->find($id);
            $user->removeFromWishlist($selectedProduct);
            $em->persist($user);
            $em->flush();

            return new JsonResponse([
                "status" => "Successfully removed"
            ]);
        }
        return new JsonResponse([
            "status" => $this->translator->trans('app.cart.controller.failed')
        ]);
    }

}
