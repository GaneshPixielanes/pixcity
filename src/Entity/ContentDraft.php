<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="pxl_content_draft")
 * @ORM\Entity(repositoryClass="App\Repository\ContentDraftRepository")
 */
class ContentDraft
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $draftType;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Card", inversedBy="yes", cascade={"persist", "remove"})
     */
    private $card;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CardProject", inversedBy="contentDraft", cascade={"persist"})
     */
    private $projectID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getDraftType(): ?int
    {
        return $this->draftType;
    }

    public function setDraftType(?int $draftType): self
    {
        $this->draftType = $draftType;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getProjectID(): ?CardProject
    {
        return $this->projectID;
    }

    public function setProjectID(?CardProject $projectID): self
    {
        $this->projectID = $projectID;

        return $this;
    }
}
