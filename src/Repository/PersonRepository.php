<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findMoviesByDirector($id){
        $builder = $this->createQueryBuilder('person');
        $builder->where('person.id = :id');
        $builder->setParameter('id', $id);
        $builder->leftJoin('person.directedMovies', 'movie');
        $builder->addSelect('movie');
        $query = $builder->getQuery();
        $result = $query->getOneOrNullResult();
        return $result;

    }

}
