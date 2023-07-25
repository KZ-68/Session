<?php

namespace App\Repository;

use App\Entity\Matiere;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Matiere>
 *
 * @method Matiere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matiere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matiere[]    findAll()
 * @method Matiere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matiere::class);
    }

    public function findNonRegisteredMatieresInSession(int $session) {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder(); 
        $qb = $sub; 
        $qb->select('m') 
            ->from('App\Entity\Matiere', 'm') 
            ->innerJoin('m.programmes', 'p', 'WITH', 'p.matiere = m.id')
            ->innerJoin('p.session', 's', 'WITH', 's.id = p.session')
            ->where('s.id = :id');
        $sub = $em->createQueryBuilder(); 
        $sub->select('ma')->from('App\Entity\Matiere', 'ma')
            ->where($sub->expr()->notIn('ma.id', $qb->getDQL()))
            ->setParameter(':id', $session);
        return $sub->getQuery()->getResult();
    ;
    }

    // Requête SQL équivalente pour la requête DQL : 
    // SELECT *
    // FROM matiere m
    // WHERE m.id NOT IN 
    // (SELECT m.id FROM matiere m 
    // INNER JOIN programme p ON p.matiere_id = m.id
    // INNER JOIN session s ON s.id = p.session_id)

//    /**
//     * @return Matiere[] Returns an array of Matiere objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Matiere
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
