<?php

namespace App\Repository;

use App\Entity\Badge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Badge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badge[]    findAll()
 * @method Badge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badge::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findWithUnlockForAction(int $user_id, string $action, int $action_count): Badge
    {
        return $this->createQueryBuilder('b')
            ->where('b.action_name = :actionName')
            ->andWhere('b.action_count = :actionCount')
            ->andWhere('u.user = :user_id OR u.user IS NULL')
            ->leftJoin('b.unlocks', 'u')
            ->select('b,u')
            ->setParameters([
                'actionCount' => $action_count,
                'actionName' => $action,
                'user_id' => $user_id
            ])
            ->getQuery()
            ->getSingleResult();
    }

    // /**
    //  * @return Badge[] Returns an array of Badge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Badge
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
