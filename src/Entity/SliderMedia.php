<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_slider_media")
 */
class SliderMedia
{
    const UPLOAD_FOLDER = "sliders";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $name = "";


    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------

    public function getUrl(){
        return "/uploads/".SliderMedia::UPLOAD_FOLDER."/".$this->getName();
    }

    public function getType(){
        $mimeType = mime_content_type("uploads/".SliderMedia::UPLOAD_FOLDER."/".$this->getName());
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
        $this->name = isset($name)?$name:"";
    }

}
