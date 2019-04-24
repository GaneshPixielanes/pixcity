<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_user_optin")
 */
class UserOptin
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $newsletter = false;


    //----------------------------------------------------
    // Pixies specific optins

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pixieCardProjectReceived = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pixieCardStatusUpdated = true;


    //----------------------------------------------------
    // Users specific optins

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $lastCardsPublished = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $lastCardsPublishedFavoritesRegions = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cardsOfTheMonth = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $myPixiesActivity = false;
    

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
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param mixed $newsletter
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * @return mixed
     */
    public function getPixieCardProjectReceived()
    {
        return isset($this->pixieCardProjectReceived)?$this->pixieCardProjectReceived:true;
    }

    /**
     * @param mixed $pixieCardProjectReceived
     */
    public function setPixieCardProjectReceived($pixieCardProjectReceived)
    {
        $this->pixieCardProjectReceived = $pixieCardProjectReceived;
    }

    /**
     * @return mixed
     */
    public function getPixieCardStatusUpdated()
    {
        return isset($this->pixieCardStatusUpdated)?$this->pixieCardStatusUpdated:true;
    }

    /**
     * @param mixed $pixieCardStatusUpdated
     */
    public function setPixieCardStatusUpdated($pixieCardStatusUpdated)
    {
        $this->pixieCardStatusUpdated = $pixieCardStatusUpdated;
    }

    /**
     * @return mixed
     */
    public function getLastCardsPublished()
    {
        return $this->lastCardsPublished;
    }

    /**
     * @param mixed $lastCardsPublished
     */
    public function setLastCardsPublished($lastCardsPublished)
    {
        $this->lastCardsPublished = $lastCardsPublished;
    }

    /**
     * @return mixed
     */
    public function getLastCardsPublishedFavoritesRegions()
    {
        return $this->lastCardsPublishedFavoritesRegions;
    }

    /**
     * @param mixed $lastCardsPublishedFavoritesRegions
     */
    public function setLastCardsPublishedFavoritesRegions($lastCardsPublishedFavoritesRegions)
    {
        $this->lastCardsPublishedFavoritesRegions = $lastCardsPublishedFavoritesRegions;
    }

    /**
     * @return mixed
     */
    public function getCardsOfTheMonth()
    {
        return $this->cardsOfTheMonth;
    }

    /**
     * @param mixed $cardsOfTheMonth
     */
    public function setCardsOfTheMonth($cardsOfTheMonth)
    {
        $this->cardsOfTheMonth = $cardsOfTheMonth;
    }

    /**
     * @return mixed
     */
    public function getMyPixiesActivity()
    {
        return $this->myPixiesActivity;
    }

    /**
     * @param mixed $myPixiesActivity
     */
    public function setMyPixiesActivity($myPixiesActivity)
    {
        $this->myPixiesActivity = $myPixiesActivity;
    }



}
