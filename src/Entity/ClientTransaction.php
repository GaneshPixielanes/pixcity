<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientTransactionRepository")
 * @ORM\Table(name="pxl_b2b_client_transaction")
 */
class ClientTransaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="clientTransactions")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $mangopayUserId;

    /**
     * @ORM\Column(type="integer")
     */
    private $mangopayWalletId;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $paymentStatus;

    /**
     * @ORM\Column(type="text")
     */
    private $mangopayResponse;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $mangopayTransactionId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserMission", inversedBy="clientTransactions")
     */
    private $mission;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transaction_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $TotalAmount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Client
    {
        return $this->user;
    }

    public function setUser(?Client $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMangopayUserId(): ?int
    {
        return $this->mangopayUserId;
    }

    public function setMangopayUserId(int $mangopayUserId): self
    {
        $this->mangopayUserId = $mangopayUserId;

        return $this;
    }

    public function getMangopayWalletId(): ?int
    {
        return $this->mangopayWalletId;
    }

    public function setMangopayWalletId(int $mangopayWalletId): self
    {
        $this->mangopayWalletId = $mangopayWalletId;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentStatus(): ?int
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(bool $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getMangopayResponse(): ?string
    {
        return $this->mangopayResponse;
    }

    public function setMangopayResponse(string $mangopayResponse): self
    {
        $this->mangopayResponse = $mangopayResponse;

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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getMangopayTransactionId(): ?int
    {
        return $this->mangopayTransactionId;
    }

    public function setMangopayTransactionId(int $mangopayTransactionId): self
    {
        $this->mangopayTransactionId = $mangopayTransactionId;

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

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(?string $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function getTransactionType(): ?string
    {
        return $this->transaction_type;
    }

    public function setTransactionType(?string $transaction_type): self
    {
        $this->transaction_type = $transaction_type;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->TotalAmount;
    }

    public function setTotalAmount(?string $TotalAmount): self
    {
        $this->TotalAmount = $TotalAmount;

        return $this;
    }
}
