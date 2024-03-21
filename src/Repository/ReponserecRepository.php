<?php

namespace App\Repository;

use App\Entity\Reponserec;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reponserec>
 *
 * @method Reponserec|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponserec|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponserec[]    findAll()
 * @method Reponserec[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponserecRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponserec::class);
    }

//    /**
//     * @return Reponserec[] Returns an array of Reponserec objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reponserec
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
