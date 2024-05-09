<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipe>
 *
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    public function recuperer(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT equipe, user 
         FROM App\Entity\Equipe equipe 
         JOIN equipe.users user'
        );

        $equipeList = $query->getResult();

        return $equipeList;
    }

    public function Nbrequipe(): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(equipe.id) AS total
         FROM App\Entity\Equipe equipe'
        );

        $equipe = $query->getSingleScalarResult(); // Use getSingleScalarResult() to get the count directly


        return (int) $equipe; // Cast the result to integer
    }

    public function countTeamsByYear()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT SUBSTRING(t.dateCreation, 1, 4) AS year, COUNT(t.id) AS teamCount
        FROM App\Entity\Equipe t
        GROUP BY year"
        );

        return $query->getResult();
    }

    public function Filter()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT e FROM App\Entity\Equipe e WHERE YEAR(e.dateCreation) = 2021 OR YEAR(e.dateCreation) = 2022"
        );

        return $query->getResult();
    }

    // EquipeRepository.php

    // EquipeRepository.php

    public function findByYear($year)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT e FROM App\Entity\Equipe e WHERE e.dateCreation >= :startOfYear AND e.dateCreation < :startOfNextYear"
        );

        $startDate = new \DateTime($year . '-01-01');
        $endDate = new \DateTime(($year + 1) . '-01-01');

        $query->setParameters([
            'startOfYear' => $startDate,
            'startOfNextYear' => $endDate,
        ]);

        return $query->getResult();
    }


    // EquipeRepository.php

    public function orderByname()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT e FROM App\Entity\Equipe e ORDER BY e.nom "
        );

        return $query->getResult();
    }
    public function orderBydate()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT e FROM App\Entity\Equipe e ORDER BY e.dateCreation "
        );

        return $query->getResult();
    }











//    /**
//     * @return Equipe[] Returns an array of Equipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Equipe
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
