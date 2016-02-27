<?php

namespace AppBundle\Repository;

class FileUploadRepository extends \Doctrine\ORM\EntityRepository
{
    public function getListUploads($limit)
    {
        return $this->createQueryBuilder('f')
            ->select('f')
            ->setMaxResults($limit)

            ->getQuery()
            ->getResult();
    }
}
