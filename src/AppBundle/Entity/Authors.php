<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Authors
 *
 * @ORM\Table(name="authors")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuthorsRepository")
 */
class Authors
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Author", type="string", length=30)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="Passw", type="string", length=255, unique=true)
     */
    private $passw;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateTime", type="datetime")
     */
    private $dateTime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Authors
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set passw
     *
     * @param string $passw
     *
     * @return Authors
     */
    public function setPassw($passw)
    {
        $this->passw = $passw;

        return $this;
    }

    /**
     * Get passw
     *
     * @return string
     */
    public function getPassw()
    {
        return $this->passw;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Authors
     */
    public function setDateTime(\DateTime $dateTime = null)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}

