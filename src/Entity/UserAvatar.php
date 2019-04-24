<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserAvatarRepository")
 * @ORM\Table(name="pxl_user_avatar")
 */
class UserAvatar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userAvatars", cascade={"persist", "remove" })
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __toString() {
        return $this->getName();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    protected function datePath(){
        return (!empty($this->getCreatedAt()))?"/".$this->getCreatedAt()->format("Y/m"):"";
    }

    public function getUrl(){
        return "/uploads/".UserMedia::UPLOAD_FOLDER.$this->datePath()."/".$this->getName();
    }

    public function getRelativeUrl(){
        return "uploads/".UserMedia::UPLOAD_FOLDER.$this->datePath()."/".$this->getName();
    }
    public function getType(){
        if (file_exists($this->getRelativeUrl())) {
            $mimeType = mime_content_type($this->getRelativeUrl());
            switch ($mimeType) {
                case "image/jpeg":
                case "image/png":
                    $type = "image";
                    break;
                default:
                    $type = "file";
            }
        }
        else{
            $type = null;
        }
        return $type;
    }
}
