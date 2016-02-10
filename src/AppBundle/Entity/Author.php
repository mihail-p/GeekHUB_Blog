<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuthorRepository")
 */
class Author implements UserInterface, \Serializable
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
     * @ORM\Column(name="Username", type="string", length=30, unique=true)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=255, unique=true)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateTime", type="datetime")
     */
    private $dateTime;

    /**
     * @var string
     * @ORM\Column(name="Role", type="string", length=30)
     */
    private $role;

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
     * @param string $user
     *
     * @return Author
     */
    public function setUsername($user)
    {
        $this->username = $user;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set passw
     *
     * @param string $passw
     *
     * @return Author
     */
    public function setPassword($passw)
    {
        $this->password = $passw;

        return $this;
    }

    /**
     * Get passw
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Author
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


    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRoles()
    {
        return [$this->getRole()];
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
}
