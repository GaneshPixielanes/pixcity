<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPacksRepository")
 * @ORM\Table(name="pxl_b2b_user_packs")
 */
class UserPacks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userPacks",fetch="EAGER"  )
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bannerImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userBasePrice;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $packPhotos;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Skill", inversedBy="userPacks", cascade={"persist", "remove"},fetch="EAGER")
     */
    private $packSkill;



    public function __construct()
    {
        $this->userPackMedia = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBannerImage(): ?string
    {
        return $this->bannerImage;
    }

    public function setBannerImage(?string $bannerImage): self
    {
        $this->bannerImage = $bannerImage;

        return $this;
    }

    public function getUserBasePrice(): ?string
    {
        return $this->userBasePrice;
    }

    public function setUserBasePrice(?string $userBasePrice): self
    {
        $this->userBasePrice = $userBasePrice;

        return $this;
    }

    public function getPackPhotos(): ?string
    {
        return $this->packPhotos;
    }

    public function setPackPhotos(?string $packPhotos): self
    {
        $this->packPhotos = $packPhotos;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }


    public function getPackSkill(): ?Skill
    {
        return $this->packSkill;
    }

    public function setPackSkill(?Skill $packSkill): self
    {
        $this->packSkill = $packSkill;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPackMedia", mappedBy="userPack",cascade={"persist"}))
     */
    private $userPackMedia;

    /**
     * @ORM\Column(type="float")
     */
    private $marginPercentage;

    /**
     * @ORM\Column(type="float")
     */
    private $marginValue;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @return Collection|UserPackMedia[]
     */
    public function getUserPackMedia(): Collection
    {
        return $this->userPackMedia;
    }

    public function addUserPackMedium(UserPackMedia $userPackMedium): self
    {
        if (!$this->userPackMedia->contains($userPackMedium)) {
            $this->userPackMedia[] = $userPackMedium;
            $userPackMedium->setUserPack($this);
        }

        return $this;
    }

    public function removeUserPackMedium(UserPackMedia $userPackMedium): self
    {
        if ($this->userPackMedia->contains($userPackMedium)) {
            $this->userPackMedia->removeElement($userPackMedium);
            // set the owning side to null (unless already changed)
            if ($userPackMedium->getUserPack() === $this) {
                $userPackMedium->setUserPack(null);
            }
        }

        return $this;
    }

    public function getMarginPercentage(): ?float
    {
        return $this->$marginPercentage;
    }

    public function setMarginPercentage(float $marginPercentage): self
    {
        $this->marginPercentage = $marginPercentage;

        return $this;
    }

    public function getMarginValue(): ?float
    {
        return $this->marginValue;
    }

    public function setMarginValue(float $marginValue): self
    {
        $this->marginValue = $marginValue;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

}
