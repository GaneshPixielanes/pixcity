<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_page_category_media")
 */
class PageCategoryMedia
{
    const UPLOAD_FOLDER = "categories";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $name = "";


    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------

    public function getUrl(){
        return "/uploads/".PageCategoryMedia::UPLOAD_FOLDER."/".$this->getName();
    }

    public function getType(){
        $mimeType = mime_content_type("uploads/".PageCategoryMedia::UPLOAD_FOLDER."/".$this->getName());
        switch($mimeType) {
            case "image/jpeg":
            case "image/png":
                $type = "image";
                break;
            default:
                $type = "file";
        }
        return $type;
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

}
