<?php

namespace App\Repository;

use App\Entity\TournamentParticipants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TournamentParticipants|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentParticipants|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentParticipants[]    findAll()
 * @method TournamentParticipants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentParticipantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentParticipants::class);
    }

    // /**
    //  * @return TournamentParticipants[] Returns an array of TournamentParticipants objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TournamentParticipants
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
