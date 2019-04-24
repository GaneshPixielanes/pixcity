<?php

namespace App\Entity;

use App\Constant\CardInfoFieldType;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_card_info")
 */
class CardInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $label = "";

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $type = "";

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mandatory = true;

    /**
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $value = "";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Card", inversedBy="infos")
     */
    private $card;


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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param mixed $card
     */
    public function setCard($card)
    {
        $this->card = $card;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getFormattedValue()
    {
        return CardInfoFieldType::getValueByType($this->getType(), $this->value);
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     */
    public function getIcon()
    {
        return CardInfoFieldType::getIconByType($this->getType());
    }

    /**
     * @return mixed
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * @param mixed $mandatory
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = isset($mandatory)?$mandatory:true;
    }

}
