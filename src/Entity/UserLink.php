<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_user_link")
 */
class UserLink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url = "";

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $type = "";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="links")
     */
    private $user;


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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        switch($this->type){
            case "facebook": return "fab fa-facebook-f";
            case "instagram": return "fab fa-instagram";
            case "twitter": return "fab fa-twitter";
            case "youtube": return "fab fa-youtube";
            case "blog": return "fab fa-blogger-b";
            case "www": return "fas fa-link";
        }
        return "";
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        switch($this->type){
            case "facebook": return "Facebook";
            case "instagram": return "Instagram";
            case "twitter": return "Twitter";
            case "youtube": return "Youtube";
            case "blog": return "Blog";
            case "www": return "Autre";
        }
        return "";
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $userId
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}
