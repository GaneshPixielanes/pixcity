<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientMissionProposalRepository")
 * @ORM\Table(name="pxl_b2b_client_mission_proposal")
 */
class ClientMissionProposal
{
    const UPLOAD_FOLDER = "proposals";
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brief;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserPacks", inversedBy="clientMissionProposals")
     */
    private $pack;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientMissionProposalMedia", mappedBy="proposal", cascade={"persist"}, orphanRemoval=true)
     */
    private $medias;

    public function __construct()
    {
//        $this->pack = new ArrayCollection();
        $this->medias = new ArrayCollection();
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

    public function getPack(): ?UserPacks
    {
        return $this->pack;
    }

    public function setPack(?UserPacks $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    public function addMedia(ClientMissionProposalMedia $media)
    {
        $media->setProposal($this);
        $this->medias[] = $media;
        return $this;
    }

    public function removeMedia(ClientMissionProposalMedia $media)
    {
        $this->medias->removeElement($media);
    }

    public function getMedias()
    {
        return $this->medias;
    }

    public function setMedias($medias)
    {
        $this->medias = $medias;
    }
}
