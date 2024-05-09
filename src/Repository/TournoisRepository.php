<?php

namespace App\Repository;

use App\Entity\Tournois;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;

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

    public function findByYear($year)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT t FROM App\Entity\Tournois t WHERE t.dateDebut >= :startOfYear AND t.dateFin < :startOfNextYear"
        );

        $startDate = new \DateTime($year . '-01-01');
        $endDate = new \DateTime(($year + 1) . '-01-01');

        $query->setParameters([
            'startOfYear' => $startDate,
            'startOfNextYear' => $endDate,
        ]);

        return $query->getResult();
    }

    public function orderByname()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT t FROM App\Entity\Tournois t ORDER BY t.nomTournois "
        );

        return $query->getResult();
    }
    public function orderBystadium()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT t FROM App\Entity\Tournois t ORDER BY t.stade "
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
