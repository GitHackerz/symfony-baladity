<?php

namespace App\Repository;

use App\Entity\DemandeAssociation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeAssociation>
 *
 * @method DemandeAssociation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeAssociation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeAssociation[]    findAll()
 * @method DemandeAssociation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeAssociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeAssociation::class);
    }

//    /**
//     * @return DemandeAssociation[] Returns an array of DemandeAssociation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DemandeAssociation
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
