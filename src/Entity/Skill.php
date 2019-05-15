<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SkillRepository")
 * @ORM\Table(name="pxl_b2b_skills")
 */
class Skill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): self
    {
        $this->created_by = $created_by;

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

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="pxl_b2b_skills_users")
     */
    private $skillUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPacks", mappedBy="relation")
     */
    private $userPacks;


    public function __construct()
    {
        $this->skillUser = new ArrayCollection();
        $this->userPacks = new ArrayCollection();
    }

    public function addUserSkill(User $user){
        $this->skillUser[] = new ArrayCollection();
    }

    /**
     * @return Collection|UserPacks[]
     */
    public function getUserPacks(): Collection
    {
        return $this->userPacks;
    }

    public function addUserPack(UserPacks $userPack): self
    {
        if (!$this->userPacks->contains($userPack)) {
            $this->userPacks[] = $userPack;
            $userPack->setRelation($this);
        }

        return $this;
    }

    public function removeUserPack(UserPacks $userPack): self
    {
        if ($this->userPacks->contains($userPack)) {
            $this->userPacks->removeElement($userPack);
            // set the owning side to null (unless already changed)
            if ($userPack->getRelation() === $this) {
                $userPack->setRelation(null);
            }
        }

        return $this;
    }


}
