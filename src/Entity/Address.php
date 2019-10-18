<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_address")
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $address = "";

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $zipcode = "";

    /**
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city = "";

    /**
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $country = "";

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $latitude = "";

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $longitude = "";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zipShortcode;

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
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param mixed $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = isset($latitude)?$latitude:"";
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = isset($longitude)?$longitude:"";;
    }

    public function getInlineAddress(){
        return $this->getAddress().", ".$this->getZipcode()." ".$this->getCity();
    }

    public function getZipShortcode(): ?string
    {
        return $this->zipShortcode;
    }

    public function setZipShortcode(?string $zipShortcode): self
    {
        $this->zipShortcode = $zipShortcode;

        return $this;
    }

}
