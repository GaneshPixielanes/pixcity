<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OutboundAnalyticsRepository")
 * @ORM\Table(name="pxl_outbound_cm")
 */
class OutboundAnalytics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="outboundAnalytics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cmPage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="outboundAnalyticsUser")
     * @ORM\JoinColumn(nullable=true)
     */
    private $endUser = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCm(): ?User
    {
        return $this->cm;
    }

    public function setCm(?User $cm): self
    {
        $this->cm = $cm;

        return $this;
    }

    public function getCmPage(): ?string
    {
        return $this->cmPage;
    }

    public function setCmPage(?string $cmPage): self
    {
        $this->cmPage = $cmPage;

        return $this;
    }

    public function getEndUser(): ?User
    {
        return $this->endUser;
    }

    public function setEndUser(?User $endUser): self
    {
        $this->endUser = $endUser;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

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
}
