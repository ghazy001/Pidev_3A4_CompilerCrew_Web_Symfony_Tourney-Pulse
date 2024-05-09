<?php

namespace App\Repository;

use App\Entity\ImagesStade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImagesStade>
 *
 * @method ImagesStade|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesStade|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesStade[]    findAll()
 * @method ImagesStade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesStadeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesStade::class);
    }

//    /**
//     * @return ImagesStade[] Returns an array of ImagesStade objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImagesStade
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
