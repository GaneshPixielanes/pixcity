<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoyaltiesRepository")
 * @ORM\Table(name="pxl_b2b_royalties")
 */
class Royalties
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserMission", inversedBy="royalties", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $mission;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="royalties", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $base_price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tax;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tax_value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $total_price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $invoice_path;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $payment_type;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     */
    private $bank_details;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $cycle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transfer_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $transfer_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payout_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $payout_date;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMission(): ?UserMission
    {
        return $this->mission;
    }

    public function setMission(UserMission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getCm(): ?User
    {
        return $this->cm;
    }

    public function setCm(User $cm): self
    {
        $this->cm = $cm;

        return $this;
    }

    public function getBasePrice(): ?string
    {
        return $this->base_price;
    }

    public function setBasePrice(string $base_price): self
    {
        $this->base_price = $base_price;

        return $this;
    }

    public function getTax(): ?string
    {
        return $this->tax;
    }

    public function setTax(string $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTaxValue(): ?string
    {
        return $this->tax_value;
    }

    public function setTaxValue(string $tax_value): self
    {
        $this->tax_value = $tax_value;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->total_price;
    }

    public function setTotalPrice(string $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getInvoicePath(): ?string
    {
        return $this->invoice_path;
    }

    public function setInvoicePath(string $invoice_path): self
    {
        $this->invoice_path = $invoice_path;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    public function setPaymentType(string $payment_type): self
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBankDetails(): ?string
    {
        return $this->bank_details;
    }

    public function setBankDetails(string $bank_details): self
    {
        $this->bank_details = $bank_details;

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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCycle(): ?int
    {
        return $this->cycle;
    }

    public function setCycle(int $cycle): self
    {
        $this->cycle = $cycle;

        return $this;
    }

    public function getTransferId(): ?string
    {
        return $this->transfer_id;
    }

    public function setTransferId(?string $transfer_id): self
    {
        $this->transfer_id = $transfer_id;

        return $this;
    }

    public function getTransferDate(): ?\DateTimeInterface
    {
        return $this->transfer_date;
    }

    public function setTransferDate(?\DateTimeInterface $transfer_date): self
    {
        $this->transfer_date = $transfer_date;

        return $this;
    }

    public function getPayoutId(): ?string
    {
        return $this->payout_id;
    }

    public function setPayoutId(?string $payout_id): self
    {
        $this->payout_id = $payout_id;

        return $this;
    }

    public function getPayoutDate(): ?\DateTimeInterface
    {
        return $this->payout_date;
    }

    public function setPayoutDate(?\DateTimeInterface $payout_date): self
    {
        $this->payout_date = $payout_date;

        return $this;
    }


}
