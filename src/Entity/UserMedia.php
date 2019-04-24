<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_user_media")
 */
class UserMedia
{
    const UPLOAD_FOLDER = "users";

    public static function uploadFolder(){
        $now = new \DateTime('now');
        return UserMedia::UPLOAD_FOLDER."/".$now->format("Y/m");
    }

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

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;



    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------


    public function __toString() {
        return $this->getUrl();
    }

    protected function datePath(){
        return (!empty($this->getUpdatedAt()))?"/".$this->getUpdatedAt()->format("Y/m"):"";
    }

    public function getUrl(){
        return "/uploads/".UserMedia::UPLOAD_FOLDER.$this->datePath()."/".$this->getName();
    }

    public function getType(){
        $mimeType = mime_content_type("uploads/".UserMedia::UPLOAD_FOLDER.$this->datePath()."/".$this->getName());
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
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}
