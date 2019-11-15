<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity(repositoryClass="App\Repository\MissionRecurringRepository")
 * @ORM\Table(name="pxl_b2b_mission_recurring")
 */
class MissionRecurring
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="mission")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserMission")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mission;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $card_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $card_id;

    /**
     * @ORM\Column(type="date")
     */
    private $payment_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_status;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $card_expiration_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $card_alias;

    /**
     * @ORM\Column(type="text")
     */
    private $card_response;

    /**
     * @ORM\Column(type="date")
     */
    private $invoice_date;

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

    public function getMission(): ?UserMission
    {
        return $this->mission;
    }

    public function setMission(?UserMission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getCardType(): ?string
    {
        return $this->card_type;
    }

    public function setCardType(string $card_type): self
    {
        $this->card_type = $card_type;

        return $this;
    }

    public function getCardId(): ?int
    {
        return $this->card_id;
    }

    public function setCardId(int $card_id): self
    {
        $this->card_id = $card_id;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): self
    {
        $this->payment_date = $payment_date;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(string $payment_status): self
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCardExpirationDate(): ?string
    {
        return $this->card_expiration_date;
    }

    public function setCardExpirationDate(string $card_expiration_date): self
    {
        $this->card_expiration_date = $card_expiration_date;

        return $this;
    }

    public function getCardAlias(): ?string
    {
        return $this->card_alias;
    }

    public function setCardAlias(string $card_alias): self
    {
        $this->card_alias = $card_alias;

        return $this;
    }

    public function getCardResponse(): ?string
    {
        return $this->card_response;
    }

    public function setCardResponse(string $card_response): self
    {
        $this->card_response = $card_response;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(\DateTimeInterface $invoice_date): self
    {
        $this->invoice_date = $invoice_date;

        return $this;
    }
}
