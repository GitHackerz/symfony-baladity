<?php

namespace App\Repository;

use App\Entity\TacheCommentaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TacheCommentaires>
 *
 * @method TacheCommentaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method TacheCommentaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method TacheCommentaires[]    findAll()
 * @method TacheCommentaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TacheCommentairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TacheCommentaires::class);
    }

//    /**
//     * @return TacheCommentaires[] Returns an array of TacheCommentaires objects
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

//    public function findOneBySomeField($value): ?TacheCommentaires
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
