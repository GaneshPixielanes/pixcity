<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstagramTrendsRepository")
 * @ORM\Table(name="pxl_instagram_trends")
 */
class InstagramTrends
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="instagramTrends")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $noOfPosts;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $noOfFollowers;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $noOfFollowed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $response;

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

    public function getNoOfPosts(): ?string
    {
        return $this->noOfPosts;
    }

    public function setNoOfPosts(?string $noOfPosts): self
    {
        $this->noOfPosts = $noOfPosts;

        return $this;
    }

    public function getNoOfFollowers(): ?string
    {
        return $this->noOfFollowers;
    }

    public function setNoOfFollowers(?string $noOfFollowers): self
    {
        $this->noOfFollowers = $noOfFollowers;

        return $this;
    }

    public function getNoOfFollowed(): ?string
    {
        return $this->noOfFollowed;
    }

    public function setNoOfFollowed(?string $noOfFollowed): self
    {
        $this->noOfFollowed = $noOfFollowed;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): self
    {
        $this->response = $response;

        return $this;
    }
}
