<?php

namespace AppBundle\Repository;

class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPosts()
    {
        return $this->createQueryBuilder('p')
            ->select('au.author' ,'p.title', 'p.dateTime', 'p.post', 'pt.tag')
            ->join('p.author', 'au')
            ->join('p.tags', 'pt')
            ->getQuery()
            ->getResult();
    }
}
