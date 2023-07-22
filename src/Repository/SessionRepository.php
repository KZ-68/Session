<?php

namespace App\Repository;

use App\Entity\Session;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function findCurrentSessionsByDate() :array {
        
        return $this->createQueryBuilder('s')
        ->andWhere('CURRENT_DATE() > s.dateDebut AND CURRENT_DATE() < s.dateFin')
        ->orderBy('s.dateDebut', 'ASC')
        ->getQuery()
        ->getResult()
    ;
    }

    public function findNextSessionsByDate() :array {
        
        return $this->createQueryBuilder('s')
        ->andWhere('CURRENT_DATE() < s.dateDebut AND CURRENT_DATE() < s.dateFin')
        ->orderBy('s.dateDebut', 'ASC')
        ->getQuery()
        ->getResult()
    ;
    }

    public function findOldSessionsByDate() :array {
        
        return $this->createQueryBuilder('s')
        ->andWhere('CURRENT_DATE() > s.dateDebut AND CURRENT_DATE() > s.dateFin')
        ->orderBy('s.dateDebut', 'ASC')
        ->getQuery()
        ->getResult()
    ;
    }

//    /**
//     * @return Session[] Returns an array of Session objects
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

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
