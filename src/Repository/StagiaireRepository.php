<?php

namespace App\Repository;

use App\Entity\Stagiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stagiaire>
 *
 * @method Stagiaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stagiaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stagiaire[]    findAll()
 * @method Stagiaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StagiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stagiaire::class);
    }

    public function findNonRegisteredStagiairesInSession(int $session) {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder(); 
        $qb = $sub; 
        $qb->select('s') 
            ->from('App\Entity\Stagiaire', 's') 
            ->leftJoin('s.sessions', 'se') 
            ->where('se.id = :id');
        $sub = $em->createQueryBuilder();
        $sub->select('st')->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            ->setParameter(':id', $session);
        return $sub->getQuery()->getResult();

        // $stg = $this->createQueryBuilder('sa1')
        // ->select('sa1.prenom', 'sa1.nom')
        // ->leftJoin('sa1.sessions', 'session')
        // ->where('session.id = :id');
        
        // $nonStg = $this->createQueryBuilder('sa')
        // ->where('sa.id NOT IN ('.$stg->getDQL().')')
        // ->setParameter(':id', $session);
        // $stg = $nonStg;
        // return $nonStg->getQuery()
        // ->getResult()
    ;
    }

//    /**
//     * @return Stagiaire[] Returns an array of Stagiaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stagiaire
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
