<?php

namespace AppBundle\Repository;

class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPosts()
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'pt', 'au')
            ->join('p.author', 'au')
            ->leftJoin('p.tags', 'pt')
            ->getQuery()
            ->getResult();
    }
}