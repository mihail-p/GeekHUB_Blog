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

    public function getPostsWithTag($tag)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'au')
            ->join('p.author', 'au')
            ->leftJoin('p.tags', 'pt')
            ->where('pt.tag = :tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult();
    }

    public function search($query)
    {
        return $this->createQueryBuilder('p')
            ->select('p, pt')
            ->leftJoin('p.tags', 'pt')
            ->orWhere('pt.tag = :q')
            ->orWhere("p.post LIKE '%$query%'")
            ->orWhere("p.title LIKE '%$query%'")
            ->setParameter('q', $query)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}