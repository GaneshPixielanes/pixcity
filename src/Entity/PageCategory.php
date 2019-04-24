<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PageCategoryRepository")
 * @ORM\Table(name="pxl_page_category")
 */
class PageCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $name = "";

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $discover = "";

    /**
     * @Gedmo\Slug(fields={"meta_title"}, updatable=false)
     * @ORM\Column(length=128, unique=true)
     * @Assert\Length(max=128)
     */
    private $slug = "";

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     */
    private $meta_title = "";

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     */
    private $meta_description = "";

    /**
     * @ORM\Column(type="text")
     */
    private $content = "";

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $indexed = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hidden = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     */
    private $region;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PageCategoryMedia", cascade={"persist"}, orphanRemoval=true)
     */
    private $thumb;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PageCategoryMedia", cascade={"persist"}, orphanRemoval=true)
     */
    private $background;

    /**
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $facebook = "";

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title)
    {
        $this->meta_title = $meta_title;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description)
    {
        $this->meta_description = $meta_description;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = isset($content)?$content:"";
    }

    /**
     * @return mixed
     */
    public function getIndexed()
    {
        return $this->indexed;
    }

    /**
     * @param mixed $indexed
     */
    public function setIndexed($indexed)
    {
        $this->indexed = $indexed;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param mixed $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getDiscover()
    {
        return $this->discover;
    }

    /**
     * @param mixed $discover
     */
    public function setDiscover($discover)
    {
        $this->discover = $discover;
    }

    /**
     * @return mixed
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @param mixed $thumb
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    }

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param mixed $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = isset($facebook)?$facebook:"";
    }



}
