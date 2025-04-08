<?php

namespace JiguangPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JiguangPushBundle\Entity\Push;

/**
 * @method Push|null find($id, $lockMode = null, $lockVersion = null)
 * @method Push|null findOneBy(array $criteria, array $orderBy = null)
 * @method Push[] findAll()
 * @method Push[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PushRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Push::class);
    }
}
