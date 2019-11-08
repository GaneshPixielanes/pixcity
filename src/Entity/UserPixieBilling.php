<?php

namespace App\Entity;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Ddeboer\VatinBundle\Validator\Constraints\Vatin;

/**
 * @ORM\Entity
 * @ORM\EntityListeners({"App\Entity\Listener\UserPixieBillingListener"})
 * @ORM\Table(name="pxl_user_pixie_billing")
 */
class UserPixieBilling
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $status = "";

    /**
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $companyName = '';

    /**
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $firstname = "";

    /**
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $lastname = "";

    /**
     * @Vatin(message="error.vat")
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tva = "";

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", cascade={"persist"}, orphanRemoval=true)
     */
    private $address;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=16)
     */
    private $phone = "";

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20)
     */
    private $billingType = "";

    /**
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $billingName = "";

    /**
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $billingCountry = "";

    /**
     * @Assert\Iban(message="error.iban")
     * @ORM\Column(type="string", length=40)
     */
    private $billingIban = "";

    /**
     * @Assert\Bic(message="error.bic")
     * @ORM\Column(type="string", length=40)
     */
    private $billingBic = "";

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "error.image.ext"
     * )
     */
    private $rib = "";

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $revolutId = "";

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $needRevolutUpdate = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rcs;

    /**
     * @ORM\Column(type="integer")
     */
    private $mangopay_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mangopay_need_to_update;



    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = isset($companyName)?$companyName:"";
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = isset($firstname)?$firstname:"";;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = isset($lastname)?$lastname:"";;
    }

    /**
     * @return mixed
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param mixed $tva
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getBillingType()
    {
        return $this->billingType;
    }

    /**
     * @param mixed $billingType
     */
    public function setBillingType($billingType)
    {
        $this->billingType = $billingType;
    }

    /**
     * @return mixed
     */
    public function getBillingName()
    {
        return $this->billingName;
    }

    /**
     * @param mixed $billingName
     */
    public function setBillingName($billingName)
    {
        $this->billingName = $billingName;
    }

    /**
     * @return mixed
     */
    public function getBillingCountry()
    {
        return $this->billingCountry;
    }

    /**
     * @param mixed $billingCountry
     */
    public function setBillingCountry($billingCountry)
    {
        $this->billingCountry = isset($billingCountry)?$billingCountry:"";;
    }

    /**
     * @return mixed
     */
    public function getBillingIban()
    {
        return $this->billingIban;
    }

    /**
     * @param mixed $billingIban
     */
    public function setBillingIban($billingIban)
    {
        $this->billingIban = isset($billingIban)?$billingIban:"";;
    }

    /**
     * @return mixed
     */
    public function getBillingBic()
    {
        return $this->billingBic;
    }

    /**
     * @param mixed $billingBic
     */
    public function setBillingBic($billingBic)
    {
        $this->billingBic = isset($billingBic)?$billingBic:"";;
    }

    /**
     * @return mixed
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * @param mixed $rib
     */
    public function setRib($rib)
    {
        $this->rib = isset($rib)?$rib:"";;
    }

    public function getRibUrl()
    {
        $fileName = ($this->getRib() instanceof File)?$this->getRib()->getFilename():$this->getRib();
        return ($fileName)?"uploads/rib/".$fileName:null;
    }


    /**
     * @return mixed
     */
    public function getRevolutId()
    {
        return $this->revolutId;
    }

    /**
     * @param mixed $revolutId
     */
    public function setRevolutId($revolutId)
    {
        $this->revolutId = $revolutId;
    }

    /**
     * @return mixed
     */
    public function getNeedRevolutUpdate()
    {
        return $this->needRevolutUpdate;
    }

    /**
     * @param mixed $needRevolutUpdate
     */
    public function setNeedRevolutUpdate($needRevolutUpdate)
    {
        $this->needRevolutUpdate = $needRevolutUpdate;
    }

    public function getRcs(): ?string
    {
        return $this->rcs;
    }

    public function setRcs(?string $rcs): self
    {
        $this->rcs = $rcs;

        return $this;
    }

    public function getMangopayId(): ?int
    {
        return $this->mangopay_id;
    }

    public function setMangopayId(int $mangopay_id): self
    {
        $this->mangopay_id = $mangopay_id;

        return $this;
    }

    public function getMangopayNeedToUpdate(): ?int
    {
        return $this->mangopay_need_to_update;
    }

    public function setMangopayNeedToUpdate(?int $mangopay_need_to_update): self
    {
        $this->mangopay_need_to_update = $mangopay_need_to_update;

        return $this;
    }


}
