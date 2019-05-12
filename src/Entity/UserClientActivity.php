<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserClientActivityRepository")
 * @ORM\Table(name="pxl_b2b_user_client_activity")
 */
class UserClientActivity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserMission", inversedBy="userClientActivities")
     */
    private $mission;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userActivities")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $actionSequence;

    /**
     * @ORM\Column(type="datetime")
     */
    private $actionAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $params;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getActionSequence(): ?string
    {
        return $this->actionSequence;
    }

    public function setActionSequence(?string $actionSequence): self
    {
        $this->actionSequence = $actionSequence;

        return $this;
    }

    public function getActionAt(): ?\DateTimeInterface
    {
        return $this->actionAt;
    }

    public function setActionAt(\DateTimeInterface $actionAt): self
    {
        $this->actionAt = $actionAt;

        return $this;
    }

    public function getParams(): ?string
    {
        return $this->params;
    }

    public function setParams(?string $params): self
    {
        $this->params = $params;

        return $this;
    }
}
