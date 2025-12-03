<?php

namespace App\Repository;

use App\Entity\Painting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Painting>
 */
class PaintingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Painting::class);
    }

    /**
     * @return Painting[] Returns an array of published Painting objects
     */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = :val')
            ->setParameter('val', true)
            ->orderBy('p.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Painting[] Returns paintings by category
     */
    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :categoryId')
            ->andWhere('p.isPublished = :published')
            ->setParameter('categoryId', $categoryId)
            ->setParameter('published', true)
            ->orderBy('p.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Painting[] Returns paintings by user
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('p.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Painting[] Returns recent published paintings
     */
    public function findRecent(int $limit = 6): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('p.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Recherche et tri des peintures publiées
     * 
     * @param string|null $search Terme de recherche (titre, description)
     * @param int|null $categoryId ID de la catégorie
     * @param string $sortBy Champ de tri (created, title, category)
     * @param string $sortOrder Ordre de tri (ASC, DESC)
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findPublishedWithSearchAndSort(
        ?string $search = null,
        ?int $categoryId = null,
        string $sortBy = 'created',
        string $sortOrder = 'DESC'
    ): \Doctrine\ORM\QueryBuilder {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->where('p.isPublished = :published')
            ->setParameter('published', true);

        // Recherche par terme
        if ($search) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('p.title', ':search'),
                    $qb->expr()->like('p.description', ':search')
                )
            )
            ->setParameter('search', '%' . $search . '%');
        }

        // Filtre par catégorie
        if ($categoryId) {
            $qb->andWhere('p.category = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }

        // Tri
        $allowedSortFields = ['created', 'title', 'category'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'created';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        switch ($sortBy) {
            case 'title':
                $qb->orderBy('p.title', $sortOrder);
                break;
            case 'category':
                $qb->orderBy('c.name', $sortOrder);
                break;
            case 'created':
            default:
                $qb->orderBy('p.created', $sortOrder);
                break;
        }

        return $qb;
    }
}

