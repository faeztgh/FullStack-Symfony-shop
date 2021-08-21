<?php

namespace App\Repository;

use App\Controller\CartController;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CartItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartItem[]    findAll()
 * @method CartItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    // /**
    //  * @return CartItem[] Returns an array of CartItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /**
     * @throws NonUniqueResultException
     */
    public function findOneByCart($value): ?CartItem
    {

        return $this->createQueryBuilder('c')
            ->andWhere('c.cart = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();

    }


    /**
     * @throws NonUniqueResultException
     */
    public function findByCartAndProductUncheckedOut($cart, $product)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.cart = :cart')
            ->andWhere('c.product = :product')
            ->andWhere('c.status is null')
            ->setParameter('cart', $cart)
            ->setParameter('product', $product)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUncheckedOutCartItems()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status is null')
            ->getQuery()
            ->getResult();
    }

    public function getCheckedOutCartItems()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status')
            ->setParameter('status', CartController::CART_ITEM_STATUS_CHECKED_OUT)
            ->getQuery()
            ->getResult();
    }


}
