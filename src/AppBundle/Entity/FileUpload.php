<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileUploadRepository")
 * @Gedmo\Uploadable(path="./media", callback="myCallbackMethod", filenameGenerator="SHA1", appendNumber=true, allowedTypes="image/png,image/jpeg,image/jpg,image/gif,image/x-ms-bmp")
 */

class FileUpload
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @ORM\Column
     * @Gedmo\UploadableFileName
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max="255")
     */
    private $origName;

    /**
     * @ORM\Column
     * @Gedmo\UploadableFileMimeType
     */
    private $mimeType;

    /**
     * @ORM\Column(type="decimal")
     * @Gedmo\UploadableFileSize
     */
    private $size;

    /**
     * @Assert\File(maxSize="5000000")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Post", inversedBy="uploadFile")
     */
    private $post;

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function myCallbackMethod(array $info)
    {
            /*$this->setOrigName($info['fileName']);*/

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return FileUpload
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FileUpload
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getOrigName()
    {
        return $this->origName;
    }

    /**
     * @param mixed $origName
     */
    public function setOrigName($origName)
    {
        $this->origName = $origName;
    }

    /**
     * @return mixed
     */
    public function getOrigNameSize()
    {
        $tmpName = $this->origName;
        if ($this->origName == ''){
            $tmpName='empty';
        }
        return $tmpName.' ('.$this->size.')';
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return FileUpload
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return FileUpload
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return FileUpload
     */
    public function setPost(\AppBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
