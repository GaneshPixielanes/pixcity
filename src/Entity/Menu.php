<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 * @ORM\Table(name="pxl_menu")
 */
class Menu
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
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @Assert\Length(max=128)
     * @ORM\Column(length=128, unique=true)
     */
    private $slug = "";

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuItem", mappedBy="menu", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $items;

    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------


    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addItem(MenuItem $item)
    {
        $item->setMenu($this);
        $this->items[] = $item;
        return $this;
    }

    public function removeItem(MenuItem $item)
    {
        $this->items->removeElement($item);
    }


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
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }


}
