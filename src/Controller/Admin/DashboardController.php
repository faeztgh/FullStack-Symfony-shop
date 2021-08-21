<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Contact;
use App\Entity\Discount;
use App\Entity\Product;
use App\Entity\Ticket;
use App\Entity\User;
use App\Services\GetTopProductService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @var GetTopProductService
     */
    private GetTopProductService $getTopProductService;
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * DashboardController constructor.
     * @param GetTopProductService $getTopProductService
     * @param TranslatorInterface $translator
     */
    public function __construct(GetTopProductService $getTopProductService, TranslatorInterface $translator)
    {
        $this->getTopProductService = $getTopProductService;
        $this->translator = $translator;
    }


    /**
     * @Route("/admin", name="admin")
     * @throws Exception
     */
    public function index(): Response
    {


        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)
            ->findAll();
        $products = $em->getRepository(Product::class)
            ->findAll();
        $purchasedItems = $em->getRepository(CartItem::class)
            ->getCheckedOutCartItems();
        $unPurchasedItems = $em->getRepository(CartItem::class)
            ->getUncheckedOutCartItems();

        /**
         * @var Product[] $mostViewedProducts
         */
        $mostViewedProducts = $this->getTopProductService
            ->getTopFromProduct('p.views', 'DESC', 11);
        $mostViewedResNumbers = [];
        $mostViewedResNames = [];

        foreach ($mostViewedProducts as $item) {
            array_push($mostViewedResNumbers, $item->getViews());
            array_push($mostViewedResNames, $item->getName());
        }


        /**
         * @var Product[] $leastQuantityProducts
         */
        $leastQuantityProducts = $this->getTopProductService
            ->getTopFromProduct('p.quantity', 'ASC', 11);
        $leastQuantityNumbers = [];
        foreach ($leastQuantityProducts as $item) {
            array_push($leastQuantityNumbers, $item->getQuantity());
        }

        return $this->render('admin/index.html.twig', [
            "usersCount" => count($users),
            "productsCount" => count($products),
            "purChasedItems" => count($purchasedItems),
            "unPurChasedItems" => count($unPurchasedItems),
            "users" => $users,
            "page_title" => $this->translator->trans('app.admin.home.Admin Dashboard'),
            "mostViewProductsNumbers" => $mostViewedResNumbers,
            "mostViewProductsNames" => $mostViewedResNames,
            "leastQuantityNumbers" => $leastQuantityNumbers

        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->translator->trans('app.admin.home.Admin Dashboard'))
            ->setFaviconPath('build/img/logo.png');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('app.admin.page_title', 'fa fa-home');
        yield MenuItem::linkToCrud('app.admin.sidebar.products', 'fas fa-shopping-bag', Product::class);
        yield MenuItem::linkToCrud('app.admin.sidebar.discount', 'fas fa-percent', Discount::class);
        yield MenuItem::linkToCrud('app.admin.sidebar.contact_message', 'fas fa-inbox', Contact::class);
        yield MenuItem::linkToCrud('app.admin.sidebar.tickets', 'fas fa-ticket-alt', Ticket::class);

        yield MenuItem::section('app.admin.sidebar.cart', 'fas fa-money-check-alt')->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('app.admin.sidebar.cart', 'fas fa-money-check', Cart::class);
        yield MenuItem::linkToCrud('app.admin.sidebar.cart_item', 'far fa-credit-card', CartItem::class);

        yield MenuItem::section('app.admin.sidebar.super_admin_menu', 'fas fa-user-shield')->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('app.admin.sidebar.users', 'fas fa-user', User::class)->setPermission('ROLE_SUPER_ADMIN');
    }


}

