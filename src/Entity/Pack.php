<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PackRepository")
 * @ORM\Table("pxl_b2b_pack")
 */
class Pack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;



    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPacks", mappedBy="pack", cascade={"persist", "remove"})
     */
    private $userPacks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMission", mappedBy="referencePack", cascade={"persist", "remove"})
     */
    private $packMission;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClientMissionProposal", mappedBy="pack")
     */
    private $clientMissionProposalsPack;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="packs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $admin;

    public function __construct()
    {
        $this->clientMissionProposalsPack = new ArrayCollection();
    }

    public function __toString() {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserPacks(): ?UserPacks
    {
        return $this->userPacks;
    }

    public function setUserPacks(UserPacks $userPacks): self
    {
        $this->userPacks = $userPacks;

        // set the owning side of the relation if necessary
        if ($this !== $userPacks->getPack()) {
            $userPacks->setPack($this);
        }

        return $this;
    }

    public function getPackMission(): ?UserMission
    {
        return $this->packMission;
    }

    public function setPackMission(?UserMission $packMission): self
    {
        $this->packMission = $packMission;

        // set (or unset) the owning side of the relation if necessary
        $newReferencePack = $packMission === null ? null : $this;
        if ($newReferencePack !== $packMission->getReferencePack()) {
            $packMission->setReferencePack($newReferencePack);
        }

        return $this;
    }

    /**
     * @return Collection|ClientMissionProposal[]
     */
    public function getClientMissionProposalsPack(): Collection
    {
        return $this->clientMissionProposalsPack;
    }

    public function addClientMissionProposalsPack(ClientMissionProposal $clientMissionProposalsPack): self
    {
        if (!$this->clientMissionProposalsPack->contains($clientMissionProposalsPack)) {
            $this->clientMissionProposalsPack[] = $clientMissionProposalsPack;
            $clientMissionProposalsPack->addPack($this);
        }

        return $this;
    }

    public function removeClientMissionProposalsPack(ClientMissionProposal $clientMissionProposalsPack): self
    {
        if ($this->clientMissionProposalsPack->contains($clientMissionProposalsPack)) {
            $this->clientMissionProposalsPack->removeElement($clientMissionProposalsPack);
            $clientMissionProposalsPack->removePack($this);
        }

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

}
