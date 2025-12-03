<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[] Returns an array of visible Comment objects
     */
    public function findVisibleByPainting(int $paintingId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.painting = :paintingId')
            ->andWhere('c.isVisible = :visible')
            ->setParameter('paintingId', $paintingId)
            ->setParameter('visible', true)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}



