<?php

namespace App\Repository;

use App\Entity\TacheProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TacheProjet>
 *
 * @method TacheProjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method TacheProjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method TacheProjet[]    findAll()
 * @method TacheProjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TacheProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TacheProjet::class);
    }

//    /**
//     * @return TacheProjet[] Returns an array of TacheProjet objects
//     */
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

//    public function findOneBySomeField($value): ?TacheProjet
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
