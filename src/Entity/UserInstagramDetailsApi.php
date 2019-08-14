<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserInstagramDetailsApiRepository")
 * @ORM\Table("pxl_user_instagram_details_api")
 */
class UserInstagramDetailsApi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NoOfPosts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $NoOfFollowers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $NoOfFollowed;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $response;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="userInstagramDetailsApi", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;
    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default" : 0})
     */
    private $processed;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoOfPosts(): ?string
    {
        return $this->NoOfPosts;
    }

    public function setNoOfPosts(?string $NoOfPosts): self
    {
        $this->NoOfPosts = $NoOfPosts;

        return $this;
    }

    public function getNoOfFollowers(): ?string
    {
        return $this->NoOfFollowers;
    }

    public function setNoOfFollowers(?string $NoOfFollowers): self
    {
        $this->NoOfFollowers = $NoOfFollowers;

        return $this;
    }

    public function getNoOfFollowed(): ?string
    {
        return $this->NoOfFollowed;
    }

    public function setNoOfFollowed(?string $NoOfFollowed): self
    {
        $this->NoOfFollowed = $NoOfFollowed;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): self
    {
        $this->response = $response;

        return $this;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProcessed(): ?int
    {
        return $this->processed;
    }

    public function setProcessed(?int $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

}
