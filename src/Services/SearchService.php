<?php


namespace App\Services;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class SearchService
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

    public function searchProducts($input)
    {
        $input = str_replace("%", "[%]", $input);
        $productRepo = $this->manager->getRepository(Product::class);

        return $productRepo
            ->createQueryBuilder('a')
            ->where('a.name like :q OR a.brand like :q OR a.model like :q OR a.briefDescription like :q')
            ->setParameter('q', '%' . $input . '%')
            ->getQuery()
            ->getResult();
    }
}