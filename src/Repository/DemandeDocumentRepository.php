<?php

namespace App\Repository;

use App\Entity\DemandeDocument;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeDocument>
 *
 * @method DemandeDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeDocument[]    findAll()
 * @method DemandeDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeDocument::class);
    }

//    /**
//     * @return DemandeDocument[] Returns an array of DemandeDocument objects
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

//    public function findOneBySomeField($value): ?DemandeDocument
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }




    /**
     * Update a Document entity.
     *
     * @param Document $document The document entity to update
     * @return void
     */
    public function Gerer_demande(DemandeDocument $ddoc): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($ddoc);
        $entityManager->flush();
    }


    //    /**
//     * @return DemandeDocument[] Returns an array of DemandeDocument objects
//     */
    public function find_Aceepted_Rejected(int $id_user): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut IN (:statuses)')
            ->setParameter('statuses', ['acceptée', 'rejetée'])
            ->andWhere('d.user = :u_id')
            ->setParameter('u_id', $id_user)

            ->orderBy('d.dateTraitement', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }

}
