<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientMissionProposalRepository")
 * @ORM\Table(name="pxl_b2b_client_mission_proposal")
 */
class ClientMissionProposal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="clientMissionProposals")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="missionProposalsToCityMaker")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pack", inversedBy="clientMissionProposalsPack")
     */
    private $pack;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brief;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->pack = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
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

    /**
     * @return Collection|pack[]
     */
    public function getPack(): Collection
    {
        return $this->pack;
    }

    public function addPack(pack $pack): self
    {
        if (!$this->pack->contains($pack)) {
            $this->pack[] = $pack;
        }

        return $this;
    }

    public function removePack(pack $pack): self
    {
        if ($this->pack->contains($pack)) {
            $this->pack->removeElement($pack);
        }

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

    public function getBrief(): ?string
    {
        return $this->brief;
    }

    public function setBrief(?string $brief): self
    {
        $this->brief = $brief;

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
}
