<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findMoviesByCategory($id){

        $builder = $this->createQueryBuilder('category');
        $builder->where('category.id = :id');
        $builder->setParameter('id', $id);
        $builder->leftJoin('category.movies', 'movie');
        $builder->addSelect('movie');
        $query = $builder->getQuery();
        $result = $query->getOneOrNullResult();
        return $result;

    }
}
