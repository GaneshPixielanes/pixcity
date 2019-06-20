<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MissionPaymentRepository")
 * @ORM\Table(name="pxl_b2b_mission_payment")
 */
class MissionPayment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="missionPayment", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserMission", inversedBy="userMissionPayment", cascade={"persist", "remove"})
     */
    private $mission;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userBasePrice;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taxPercentage;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taxValue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $transactionFee;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transactionFeeReference;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $paymentGatewayDetails;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $margin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $marginPercentage;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $adjustment;

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

    public function getMission(): ?UserMission
    {
        return $this->mission;
    }

    public function setMission(?UserMission $mission): self
    {
        $this->mission = $mission;

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

    public function getClientPrice(): ?string
    {
        return $this->clientPrice;
    }

    public function setClientPrice(?string $clientPrice): self
    {
        $this->clientPrice = $clientPrice;

        return $this;
    }

    public function getTaxPercentage(): ?float
    {
        return $this->taxPercentage;
    }

    public function setTaxPercentage(?float $taxPercentage): self
    {
        $this->taxPercentage = $taxPercentage;

        return $this;
    }

    public function getTaxValue(): ?float
    {
        return $this->taxValue;
    }

    public function setTaxValue(?float $taxValue): self
    {
        $this->taxValue = $taxValue;

        return $this;
    }

    public function getTransactionFee(): ?float
    {
        return $this->transactionFee;
    }

    public function setTransactionFee(?float $transactionFee): self
    {
        $this->transactionFee = $transactionFee;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getTransactionFeeReference(): ?string
    {
        return $this->transactionFeeReference;
    }

    public function setTransactionFeeReference(?string $transactionFeeReference): self
    {
        $this->transactionFeeReference = $transactionFeeReference;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMargin(): ?float
    {
        return $this->margin;
    }

    public function setMargin(?float $margin): self
    {
        $this->margin = $margin;

        return $this;
    }

    public function getMarginPercentage(): ?int
    {
        return $this->marginPercentage;
    }

    public function setMarginPercentage(?int $marginPercentage): self
    {
        $this->marginPercentage = $marginPercentage;

        return $this;
    }

    public function getAdjustment(): ?float
    {
        return $this->adjustment;
    }

    public function setAdjustment(?float $adjustment): self
    {
        $this->adjustment = $adjustment;

        return $this;
    }
}
