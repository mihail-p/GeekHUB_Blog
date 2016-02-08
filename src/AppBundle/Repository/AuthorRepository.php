<?php

namespace AppBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class AuthorRepository extends \Doctrine\ORM\EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function getAllAuthors()
    {
        return $this->createQueryBuilder('a')
            ->select('a.username', 'a.dateTime', 'a.password')
            ->getQuery()
            ->getResult();
    }
}
