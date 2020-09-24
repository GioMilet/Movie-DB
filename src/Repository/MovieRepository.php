<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    //cette méthode de repository custom me permet de récupérer un film et les objets character lié
    public function findWithActors($id){
        //je crée un queryBuilder sur l'objet Movie avec l'alias 'movie'
        $builder = $this->createQueryBuilder('movie');
        //je met ma condition de recherche
        $builder->where("movie.id = :id");
        //j'ajoute la valeur du parametre utilisé dans la condition
        $builder->setParameter('id', $id);

        //Je crée une jointure avec la table movieActor
        $builder->leftJoin('movie.movieActors', 'actor');
        //J'ajoute la personne au select pour que doctrine alimente les objets associés
        $builder->addSelect('actor');

        //je crée la jointure avec les personnes
        $builder->leftJoin('actor.person', 'person');
        //J'ajoute la personne au select pour que doctrine alimente les objets associés
        $builder->addSelect('person');

        $builder->leftJoin('movie.posts', 'post');
        $builder->addSelect('post');

        $builder->leftJoin('movie.director', 'director');
        $builder->addSelect('director');

        $builder->leftJoin('movie.writers', 'writer');
        $builder->addSelect('writer');

        $builder->leftJoin('movie.categories', 'category');
        $builder->addSelect('category');

        $builder->leftJoin('movie.awards', 'award');
        $builder->addSelect('award');
        // j'execute la requête
        $query = $builder->getQuery();
        // je recupére le resultat non pas sous la forme d'un tableau mais un ou 0 objets
        $result = $query->getOneOrNullResult();
        return $result;
    }

    public function searchMovies($title){
        $builder = $this->createQueryBuilder('movie');

        //Methode DQL Doctrine Query Language
        /*
        $builder->where('movie.title LIKE :title');
        $builder->setParameter('title', "%$title%");
        */
        $builder->where(
            $builder->expr()->like('movie.title', ":title")
        );
        $builder->setParameter('title', "%$title%");
        $builder->orderBy('movie.title', 'asc');
        $query = $builder->getQuery();
        $result = $query->execute();
        return $result;
        
    }

    public function homepageMovies(){
        $builder = $this->createQueryBuilder('movie');
        $builder->orderBy('movie.releaseDate', "DESC");

       
        $builder->setMaxResults(3);
        $query = $builder->getQuery();
        $result = $query->execute();
        return $result;
        
    }
}
