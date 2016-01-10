<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tags
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagsRepository")
 */
class Tags
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
     * @Assert\NotBlank()
     * @ORM\Column(name="Tag", type="string", length=50, unique=true)
     */
    private $tag;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Posts")
     */
     protected $posts;


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
     * Set tag
     *
     * @param string $tag
     *
     * @return Tags
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set posts
     *
     * @param \AppBundle\Entity\Posts $posts
     *
     * @return Tags
     */
    public function setPosts(\AppBundle\Entity\Posts $posts = null)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Get posts
     *
     * @return \AppBundle\Entity\Posts
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
