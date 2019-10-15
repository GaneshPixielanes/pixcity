<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientMissionProposalMediaRepository")
 * @ORM\Table(name="pxl_b2b_client_mission_proposal_media")
 */
class ClientMissionProposalMedia
{
    const UPLOAD_FOLDER = "proposals";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientMissionProposal", inversedBy="medias")
     */
    private $proposal;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getProposal(): ?ClientMissionProposal
    {
        return $this->proposal;
    }

    public function setProposal(?ClientMissionProposal $proposal): self
    {
        $this->proposal = $proposal;

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

    public static function tempFolder(){
        return ClientMissionProposalMedia::UPLOAD_FOLDER."/temp/";
    }

    public static function uploadFolder(){
        return ClientMissionProposalMedia::UPLOAD_FOLDER;
    }
}
