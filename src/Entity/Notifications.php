<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationsRepository")
 * @ORM\Table(name="pxl_notifications")
 */
class Notifications
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sent_to;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="notifications")
     */
    private $sentFrom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Admin", mappedBy="notificationDeletedBy")
     */
    private $deletedBy;

    public function __construct()
    {
        $this->deletedBy = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }



    public function getSentTo(): ?string
    {
        return $this->sent_to;
    }

    public function setSentTo(string $sent_to): self
    {
        $this->sent_to = $sent_to;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt( $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getSentFrom(): ?Admin
    {
        return $this->sentFrom;
    }

    public function setSentFrom(?Admin $sentFrom): self
    {
        $this->sentFrom = $sentFrom;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Collection|Admin[]
     */
    public function getDeletedBy(): Collection
    {
        return $this->deletedBy;
    }

    public function addDeletedBy(Admin $deletedBy): self
    {
        if (!$this->deletedBy->contains($deletedBy)) {
            $this->deletedBy[] = $deletedBy;
            $deletedBy->setNotificationDeletedBy($this);
        }

        return $this;
    }

    public function removeDeletedBy(Admin $deletedBy): self
    {
        if ($this->deletedBy->contains($deletedBy)) {
            $this->deletedBy->removeElement($deletedBy);
            // set the owning side to null (unless already changed)
            if ($deletedBy->getNotificationDeletedBy() === $this) {
                $deletedBy->setNotificationDeletedBy(null);
            }
        }

        return $this;
    }

}
