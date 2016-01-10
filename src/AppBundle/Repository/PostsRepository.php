<?php

namespace AppBundle\Repository;

class PostsRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPosts()
    {
        return $this->createQueryBuilder('p')
            ->select('au.author' ,'p.title', 'p.dateTime', 'p.post')
            ->join('p.author', 'au')
            ->getQuery()
            ->getResult();
    }
}
