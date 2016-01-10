<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentsRepository")
 */
class Comments
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
     * @var \DateTime
     *
     * @ORM\Column(name="DateTime", type="datetime")
     */
    private $dateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="Comment", type="string", length=255)
     */
    private $comment;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Authors")
     */
    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Posts")
     */
    protected $post;


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
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Comments
     */
    public function setDateTime($dateTime)
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

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Comments
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\Authors $author
     *
     * @return Comments
     */
    public function setAuthor(\AppBundle\Entity\Authors $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\Authors
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set post
     *
     * @param \AppBundle\Entity\Posts $post
     *
     * @return Comments
     */
    public function setPost(\AppBundle\Entity\Posts $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\Posts
     */
    public function getPost()
    {
        return $this->post;
    }
}
