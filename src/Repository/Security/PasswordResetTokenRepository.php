<?php

namespace App\Repository\Security;

use App\Entity\Security\PasswordResetToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordResetToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordResetToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordResetToken[]    findAll()
 * @method PasswordResetToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordResetTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordResetToken::class);
    }

    // /**
    //  * @return PasswordResetToken[] Returns an array of PasswordResetToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordResetToken
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
