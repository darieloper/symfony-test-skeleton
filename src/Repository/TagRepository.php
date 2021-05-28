<?php

namespace App\Repository;

use App\Entity\Tag;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends AbstractRepository
{
    protected $entity = Tag::class;

    public function getMostUsed() {
        return $this->createQueryBuilder('t')
            ->select('Count(t) as counter, t.name')
            ->innerJoin('t.products', 'p')
            ->groupBy('t.name')
            ->orderBy('counter', 'desc')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
