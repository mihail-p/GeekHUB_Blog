<?php

namespace AppBundle\Repository;

class PostsRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPosts()
    {
        return $this->createQueryBuilder('p')
            ->select('p.author', 'p.title', 'p.dateTime', 'p.post')
            ->getQuery()
            ->getResult();
    }
}
