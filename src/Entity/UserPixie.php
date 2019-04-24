<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_user_pixie")
 */
class UserPixie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $likeText = "";

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $dislikeText = "";

    /**
     * @Assert\Valid()
     * @ORM\OneToOne(targetEntity="App\Entity\UserPixieBilling", cascade={"persist"}, orphanRemoval=true)
     */
    private $billing;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Region")
     * @ORM\JoinTable(name="pxl_user_pixies_regions",
     *      joinColumns={@ORM\JoinColumn(name="pixie_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="region_id", referencedColumnName="id")}
     *      )
     */
    private $regions;



    public function __construct()
    {
        $this->regions = new ArrayCollection();
    }

    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------

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
    public function getLikeText()
    {
        return $this->likeText;
    }

    /**
     * @param mixed $likeText
     */
    public function setLikeText($likeText)
    {
        $this->likeText = isset($likeText)?$likeText:"";
    }

    /**
     * @return mixed
     */
    public function getDislikeText()
    {
        return $this->dislikeText;
    }

    /**
     * @param mixed $dislikeText
     */
    public function setDislikeText($dislikeText)
    {
        $this->dislikeText = isset($dislikeText)?$dislikeText:"";
    }


    /**
     * @return mixed
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * @param mixed $billing
     */
    public function setBilling($billing)
    {
        $this->billing = $billing;
    }

    /**
     * @return mixed
     */
    public function getRegions()
    {
        return $this->regions;
    }

    /**
     * @param mixed $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }


}
