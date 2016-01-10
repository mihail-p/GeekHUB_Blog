<?php

namespace AppBundle\Repository;

/**
 * AuthorsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AuthorsRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllAuthors()
    {
        return $this->createQueryBuilder('a')
            ->select('a.author', 'a.dateTime', 'a.passw')
            ->getQuery()
            ->getResult();
    }
}
