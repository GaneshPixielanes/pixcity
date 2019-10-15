<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientInfoRepository")
 * @ORM\Table(name="pxl_b2b_client_more")
 */
class ClientInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Client", inversedBy="clientInfo", cascade={"persist", "remove"})
     */
    private $client;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mangopayUserId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mangopayWalletId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $mangopayCreatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mangopayKycFile;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mangopayKycAddr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mangopayKycStatus;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $mangopayKycCreated;
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern = "/([0-9]){14,14}$/",
     *     message = "Invalid SIRET provided"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $business;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $companyCreationDate;



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

    public function getMangopayCreatedAt(): ?\DateTimeInterface
    {
        return $this->mangopayCreatedAt;
    }

    public function setMangopayCreatedAt(?\DateTimeInterface $mangopayCreatedAt): self
    {
        $this->mangopayCreatedAt = $mangopayCreatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMangopayKycFile()
    {
        return $this->mangopayKycFile;
    }

    /**
     * @param mixed $mangopayKycFile
     */
    public function setMangopayKycFile($mangopayKycFile)
    {
        $this->mangopayKycFile = $mangopayKycFile;
    }

    /**
     * @return mixed
     */
    public function getMangopayKycAddr()
    {
        return $this->mangopayKycAddr;
    }

    /**
     * @param mixed $mangopayKycAddr
     */
    public function setMangopayKycAddr($mangopayKycAddr)
    {
        $this->mangopayKycAddr = $mangopayKycAddr;
    }

    /**
     * @return mixed
     */
    public function getMangopayKycStatus()
    {
        return $this->mangopayKycStatus;
    }

    /**
     * @param mixed $mangopayKycStatus
     */
    public function setMangopayKycStatus($mangopayKycStatus)
    {
        $this->mangopayKycStatus = $mangopayKycStatus;
    }

    public function getMangopayKycCreated(): ?\DateTimeInterface
    {
        return $this->mangopayKycCreated;
    }

    public function setMangopayKycCreated(?\DateTimeInterface $mangopayKycCreated): self
    {
        $this->mangopayKycCreated = $mangopayKycCreated;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getBusiness()
    {
        return $this->business;
    }

    public function setBusiness($business)
    {
        $this->business = $business;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(?int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCompanyCreationDate(): ?\DateTimeInterface
    {
        return $this->companyCreationDate;
    }

    public function setCompanyCreationDate(?\DateTimeInterface $companyCreationDate): self
    {
        $this->companyCreationDate = $companyCreationDate;

        return $this;
    }


}
