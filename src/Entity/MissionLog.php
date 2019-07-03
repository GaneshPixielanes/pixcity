<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MissionLogRepository")
 * @ORM\EntityListeners({"App\Entity\Listener\MissionLogListener"})
 * @ORM\Table(name="pxl_b2b_user_mission_logs")
 */
class MissionLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserMission", inversedBy="missionLogs")
     */
    private $mission;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $briefFiles;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $confitionsAgreed;

    /**
     * @ORM\Column(type="float")
     */
    private $userBasePrice;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $paymentGatewayDetails;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

//    /**
//     * @ORM\OneToOne(targetEntity="App\Entity\UserMission", mappedBy="log")
//     */
////    private $userMisson;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMission(): ?UserMission
    {
        return $this->mission;
    }

    public function setMission(?UserMission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getBriefFiles(): ?string
    {
        return $this->briefFiles;
    }

    public function setBriefFiles(?string $briefFiles): self
    {
        $this->briefFiles = $briefFiles;

        return $this;
    }

    public function getConfitionsAgreed(): ?int
    {
        return $this->confitionsAgreed;
    }

    public function setConfitionsAgreed(?int $confitionsAgreed): self
    {
        $this->confitionsAgreed = $confitionsAgreed;

        return $this;
    }

    public function getUserBasePrice(): ?float
    {
        return $this->userBasePrice;
    }

    public function setUserBasePrice(float $userBasePrice): self
    {
        $this->userBasePrice = $userBasePrice;

        return $this;
    }

    public function getPaymentGatewayDetails(): ?string
    {
        return $this->paymentGatewayDetails;
    }

    public function setPaymentGatewayDetails(?string $paymentGatewayDetails): self
    {
        $this->paymentGatewayDetails = $paymentGatewayDetails;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;

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

}
