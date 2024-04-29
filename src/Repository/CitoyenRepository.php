<?php

namespace App\Repository;

use App\Entity\Citoyen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Citoyen>
 *
 * @method Citoyen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Citoyen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Citoyen[]    findAll()
 * @method Citoyen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitoyenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Citoyen::class);
    }

//    /**
//     * @return Citoyen[] Returns an array of Citoyen objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Citoyen
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
