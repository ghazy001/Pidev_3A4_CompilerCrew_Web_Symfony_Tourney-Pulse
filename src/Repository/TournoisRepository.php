<?php

namespace App\Repository;

use App\Entity\Tournois;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tournois>
 *
 * @method Tournois|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournois|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournois[]    findAll()
 * @method Tournois[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournoisRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournois::class);
    }



    /**
     * Find tournois by search term.
     *
     * @param string $searchTerm The search term
     * @param string $sortBy The search term
     * @param string $sortOrder The search term
     * @return array The matching tournois
     */
    public function findBySearchTerm($searchTerm)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.nomTournois LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');

        return $queryBuilder->getQuery()->getResult();
    }

    // TournoisRepository.php

    public function findAllSorted($sortBy, $sortOrder)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        // Ajout de la logique pour le tri
        switch ($sortBy) {
            case 'name':
                $queryBuilder->orderBy('t.nomTournois', $sortOrder);
                break;
            case 'date':
                $queryBuilder->orderBy('t.date', $sortOrder);
                break;
            case 'nombreMatch':
                $queryBuilder->orderBy('t.nombreMatch', $sortOrder);
                break;
            // Ajoutez d'autres cas pour d'autres colonnes de tri si nécessaire
            default:
                // Par défaut, triez par date de création
                $queryBuilder->orderBy('t.createdAt', 'DESC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function Nbrtournois(): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(t.idTournois) AS total FROM App\Entity\Tournois t'
        );

        $total = $query->getSingleScalarResult(); // Utilisez getSingleScalarResult() pour obtenir directement le résultat du compte

        return (int) $total; // Convertissez le résultat en entier
    }

    public function countTournoisByStadium()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT t.stade, COUNT(t.idTournois) AS totalTournois FROM App\Entity\Tournois t GROUP BY t.stade ORDER BY t.stade'
        );

        return $query->getResult();
    }








//    /**
//     * @return Tournois[] Returns an array of Tournois objects
//     
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tournois
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
