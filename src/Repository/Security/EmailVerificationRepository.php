<?php

namespace App\Repository\Security;

use App\Entity\Security\EmailVerification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailVerification|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailVerification|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailVerification[]    findAll()
 * @method EmailVerification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailVerificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailVerification::class);
    }

    /**
     * @param User $user
     * @return EmailVerification|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function findLastRequestForUser(User $user): ?EmailVerification
    {
        try {
            return $this->createQueryBuilder('ev')
                ->where('ev.user = :user')
                ->setParameter('user', $user)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
