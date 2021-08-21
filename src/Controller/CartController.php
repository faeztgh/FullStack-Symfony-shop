<?php


namespace App\Controller;


use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use DateTime;
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
 * Class CartController
 * @package App\Controller
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    public const CART_ITEM_STATUS_CHECKED_OUT = "CHECKED_OUT";
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
     * @return Response
     * @throws Exception
     * @Route("/", name="cart_index")
     */
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());
        $cartItems = "";
        $subTotal = 0;
        $this->denyAccessUnlessGranted("show", $user->getCart());

        if ($user->getCart()) {
            $cartItems = $user->getCart()->getUncheckedCartItem();
            $subTotal = $this->calcSubtotal($cartItems);
        }

        return $this->render('cart/index.html.twig', [
            "cartItems" => $cartItems,
            "subTotal" => $subTotal
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     * @Route("/handle-quantity", name="cart_handle_quantity")
     */
    public function handleQuantity(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {

            if ($this->getUser() != null) {
                $id = $request->request->get('id');
                $value = $request->request->get('value');

                // check if selected Item exist or not
                $existCartItem = $em->getRepository(CartItem::class)->find($id);

                if ($existCartItem) {
                    $existCartItem->setQuantity($value);
                    $existCartItem->setTotalPrice(
                        $this->calcPriceWithDiscount(
                            $existCartItem->getProduct()->getPrice(),
                            $existCartItem->getProduct()->getDiscount()->getDiscountPercent()
                        ) * $value);
                    $em->persist($existCartItem);
                    $em->flush();

                    // return subtotal on each request
                    $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());
                    $cartItems = $user->getCart()->getUncheckedCartItem();
                    $subTotal = $this->calcSubtotal($cartItems);


                    return new JsonResponse([
                        "status" => $this->translator->trans('app.cart.controller.success'),
                        "subTotal" => $subTotal
                    ]);
                } else {
                    return new JsonResponse([
                        "status" => $this->translator->trans('app.cart.controller.failed'),
                        "subTotal" => null
                    ]);
                }
            }
        }
    }

    /**
     * @param RequestStack $requestStack
     * @param Request $request
     * @return JsonResponse|void
     * @throws Exception
     * @Route("/remove", name="remove_from_cart")
     */
    public function removeFromCart(RequestStack $requestStack, Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());
        if ($request->isXmlHttpRequest()) {

            if ($this->getUser() != null) {
                $cartItemId = $request->request->get('id');
                $selectedCartItem = $em->getRepository(CartItem::class)->find($cartItemId);

                // check if selected cartItem exist or not
                if ($selectedCartItem) {
                    $em->remove($selectedCartItem);
                    $em->flush();
                    $cartItems = $user->getCart()->getUncheckedCartItem();
                    // setting session for showing in carts items in navbar
                    $session = $requestStack->getMasterRequest()->getSession();
                    $session->set("cartItems", $cartItems);

                    // return subtotal on each request
                    $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());
                    $cartItems = $user->getCart()->getUncheckedCartItem();

                    $subTotal = $this->calcSubtotal($cartItems);


                    return new JsonResponse([
                        "status" => $this->translator->trans('app.cart.controller.success'),
                        "cartItems" => count($cartItems),
                        "subTotal" => $subTotal
                    ]);
                } else {
                    return new JsonResponse([
                        "status" => $this->translator->trans('app.cart.controller.failed'),
                        "subTotal" => null
                    ]);

                }
            }
        }
    }


    /**
     * @Route("/checkout.html.twig", name="cart_checkout")
     * @throws Exception
     */
    public function checkout(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());
        $cartItems = $user->getCart()->getCartItem();
        $cart = $user->getCart();
        $date = new DateTime();
        $cartTotalPrice = 0;

        if (count($cart->getUncheckedCartItem()) > 0) {
            foreach ($cartItems as $cartItem) {
                if ($cartItem->getStatus() !== self::CART_ITEM_STATUS_CHECKED_OUT) {
                    $cartTotalPrice += $cartItem->getTotalPrice();
                    $cartItem->setStatus(self::CART_ITEM_STATUS_CHECKED_OUT);
                    $cartItem->setCheckedOutDate($date);
                    $cartItem->getProduct()->setQuantity($cartItem->getProduct()->getQuantity() - 1);
                    $em->persist($cartItem);
                }
            }
            // Handle user credit
            if ($user->getCredit() < $cartTotalPrice) {
                $this->addFlash("error", $this->translator
                    ->trans('app.cart.controller.not_enough_credit_error'));
                return $this->redirectToRoute('cart_index');
            }

            $user->setCredit($user->getCredit() - $cartTotalPrice);
            $em->persist($user);
            $em->persist($cart);
            $em->flush();
            return $this->render('cart/checkout.html.twig');
        } else {
            $this->addFlash("error", $this->translator->trans('app.cart.controller.empty_cart_error'));
            return $this->redirectToRoute('cart_index');
        }
    }


    /**
     * @param RequestStack $requestStack
     * @param Request $request
     * @return JsonResponse|void
     * @throws NonUniqueResultException
     * @throws Exception
     * @Route("/new", name="add_to_cart", methods={"POST"})
     */
    public function addToCart(RequestStack $requestStack, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());

        if ($request->isXmlHttpRequest()) {

            if ($this->getUser() != null) {
                $id = $request->request->get('id');

                $cart = $user->getCart();
                $selectedProduct = $em->getRepository(Product::class)->find($id);

                // check for product quantity
                if ($selectedProduct->getQuantity() < 1) {
                    return;
                }

                // check if selected Item exist or not
                $existCartItem = $em->getRepository(CartItem::class)->findByCartAndProductUncheckedOut($cart, $selectedProduct);

                if ($existCartItem) {
                    $existCartItem->setQuantity($existCartItem->getQuantity() + 1);
                    $existCartItem->setTotalPrice($existCartItem->getTotalPrice() +
                        $this->calcPriceWithDiscount(
                            $selectedProduct->getPrice(),
                            $selectedProduct->getDiscount()->getDiscountPercent())
                    );

                    $em->persist($existCartItem);
                } else {
                    $newCartItem = new CartItem();
                    $newCartItem->setProduct($selectedProduct);
                    $newCartItem->setCart($cart);
                    $newCartItem->setQuantity(1);
                    $newCartItem->setTotalPrice($this->calcPriceWithDiscount(
                        $selectedProduct->getPrice(),
                        $selectedProduct->getDiscount()->getDiscountPercent())
                    );

                    $em->persist($newCartItem);
                }

                $em->flush();
                $cartItems = $user->getCart()->getUncheckedCartItem();
                // setting session for showing in carts items in navbar
                $session = $requestStack->getMasterRequest()->getSession();
                $session->set("cartItems", $cartItems);

                return new JsonResponse([
                    "status" => $this->translator->trans('app.cart.controller.success'),
                    "cartItems" => count($cartItems)
                ]);
            } else {
                return new JsonResponse([
                    "status" => $this->translator->trans('app.cart.controller.failed')
                ]);
            }
        }
    }


    // functions

    /**
     * @param $cartItems
     * @return float|int
     */
    public function calcSubtotal($cartItems)
    {
        $subTotal = 0;
        foreach ($cartItems as $cartItem) {
            $subTotal += $cartItem->getQuantity() *
                $this->calcPriceWithDiscount(
                    $cartItem->getProduct()->getPrice(),
                    $cartItem->getProduct()->getDiscount()->getDiscountPercent()
                );
        }
        return $subTotal;
    }


    /**
     * @param $price
     * @param $discountPercentage
     * @return float|int
     */
    public function calcPriceWithDiscount($price, $discountPercentage)
    {
        return $price - (($discountPercentage / 100) * $price);
    }
}

