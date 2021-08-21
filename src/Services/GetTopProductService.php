<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class GetTopProductService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * SearchService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $attr
     * @param string $orderBy
     * @param int $maxRes
     * @return int|mixed|string
     */
    public function getTopFromProduct(string $attr, string $orderBy = 'DESC', int $maxRes = 3)
    {
        $em = $this->manager;
        $productRepo = $em->getRepository(Product::class);
        return $productRepo
            ->createQueryBuilder('p')
            ->select('p')
            ->orderBy($attr, $orderBy)
            ->setMaxResults($maxRes)
            ->getQuery()
            ->getResult();
    }
}