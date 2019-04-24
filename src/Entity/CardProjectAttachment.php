<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_card_project_attachment")
 */
class CardProjectAttachment
{
    const UPLOAD_FOLDER = "projects";

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
     * @ORM\ManyToOne(targetEntity="App\Entity\CardProject", inversedBy="attachments")
     */
    private $project;



    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------

    public function getUrl(){
        return "/uploads/".CardProjectAttachment::UPLOAD_FOLDER."/".$this->getName();
    }

    public function getType(){
        $mimeType = mime_content_type("uploads/".CardProjectAttachment::UPLOAD_FOLDER."/".$this->getName());
        switch($mimeType) {
            case "image/jpeg":
            case "image/png":
                $type = "image";
                break;
            case "application/pdf":
                $type = "pdf";
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
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

}
