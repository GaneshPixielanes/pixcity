<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MissionRecurringPriceLogRepository")
 * @ORM\Table(name="pxl_b2b_mission_recurring_price_log")
 */
class MissionRecurringPriceLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserMission", inversedBy="active_log")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mission;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MissionLog", inversedBy="recurring_price")
     * @ORM\JoinColumn(nullable=false)
     */
    private $active_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $cycle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $month;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $year;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

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

    public function getActivePrice(): ?MissionLog
    {
        return $this->active_price;
    }

    public function setActivePrice(?MissionLog $active_price): self
    {
        $this->active_price = $active_price;

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

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
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

    public function getClientInvoice(){
        $cycle = ($this->getCycle() - 1);
        return base64_encode("invoices/Recurring/".$this->getMission()->getId().'/'.$cycle.'/PX-'.$this->getMission()->getId().'-'.$this->getActivePrice()->getId().'-client.pdf');
    }

    public function getCMInvoice(){
        $cycle = ($this->getCycle() - 1);
        return base64_encode("invoices/Recurring/".$this->getMission()->getId().'/'.$cycle.'/PX-'.$this->getMission()->getId().'-'.$this->getActivePrice()->getId().'-cm.pdf');
    }
}
