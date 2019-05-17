<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserMissionRepository")
 * @ORM\Table(name="pxl_b2b_user_mission")
 */
class UserMission
{
    const UPLOAD_FOLDER = "missions";
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userMission")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="userMission")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserPacks", inversedBy="packMission", cascade={"persist", "remove"})
     */
    private $referencePack;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bannerImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $briefFiles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $missionBasePrice;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dueDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $conditionsAgreed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $axaInsurance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $generalConditionsBrief;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $missionAgreedClient;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MissionPayment", mappedBy="mission", cascade={"persist", "remove"})
     */
    private $userMissionPayment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserClientActivity", mappedBy="mission")
     */
    private $userClientActivities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="userMissions")
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MissionMedia", mappedBy="mission",cascade={"persist"})
     */
    private $missionMedia;

    public function __construct()
    {
        $this->userClientActivities = new ArrayCollection();
        $this->missionMedia = new ArrayCollection();
    }

    protected function datePath(){
        return (!empty($this->getCreatedAt()))?"/".$this->getCreatedAt()->format("Y/m"):"";
    }

    public static function tempFolder(){
        return UserMission::UPLOAD_FOLDER."/temp/";
    }

    public static function uploadFolder(){
        return UserMission::UPLOAD_FOLDER;
    }

    public function getBannerUrl(){
        return "/uploads/".UserMission::UPLOAD_FOLDER.'/'.$this->getId()."/".$this->getBannerImage();
    }

    public function getBriefUrl(){
        return "uploads/".UserMission::UPLOAD_FOLDER.'/'.$this->getId()."/".$this->getBriefFiles();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getReferencePack(): ?UserPacks
    {
        return $this->referencePack;
    }

    public function setReferencePack(?UserPacks $referencePack): self
    {
        $this->referencePack = $referencePack;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBannerImage(): ?string
    {
        return $this->bannerImage;
    }

    public function setBannerImage(?string $bannerImage): self
    {
        $this->bannerImage = $bannerImage;

        return $this;
    }

    public function getBriefFiles(): ?string
    {
        return $this->briefFiles;
    }

    public function setBriefFiles(?string $briefFiles): self
    {
        $this->briefFiles = $briefFiles;

        return $this;
    }

    public function getMissionBasePrice(): ?string
    {
        return $this->missionBasePrice;
    }

    public function setMissionBasePrice(?string $missionBasePrice): self
    {
        $this->missionBasePrice = $missionBasePrice;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getConditionsAgreed(): ?bool
    {
        return $this->conditionsAgreed;
    }

    public function setConditionsAgreed(?bool $conditionsAgreed): self
    {
        $this->conditionsAgreed = $conditionsAgreed;

        return $this;
    }

    public function getAxaInsurance(): ?bool
    {
        return $this->axaInsurance;
    }

    public function setAxaInsurance(?bool $axaInsurance): self
    {
        $this->axaInsurance = $axaInsurance;

        return $this;
    }

    public function getGeneralConditionsBrief(): ?string
    {
        return $this->generalConditionsBrief;
    }

    public function setGeneralConditionsBrief(?string $generalConditionsBrief): self
    {
        $this->generalConditionsBrief = $generalConditionsBrief;

        return $this;
    }

    public function getMissionAgreedClient(): ?string
    {
        return $this->missionAgreedClient;
    }

    public function setMissionAgreedClient(?string $missionAgreedClient): self
    {
        $this->missionAgreedClient = $missionAgreedClient;

        return $this;
    }

    public function getUserMissionPayment(): ?MissionPayment
    {
        return $this->userMissionPayment;
    }

    public function setUserMissionPayment(?MissionPayment $userMissionPayment): self
    {
        $this->userMissionPayment = $userMissionPayment;

        // set (or unset) the owning side of the relation if necessary
        $newMission = $userMissionPayment === null ? null : $this;
        if ($newMission !== $userMissionPayment->getMission()) {
            $userMissionPayment->setMission($newMission);
        }

        return $this;
    }

    /**
     * @return Collection|UserClientActivity[]
     */
    public function getUserClientActivities(): Collection
    {
        return $this->userClientActivities;
    }

    public function addUserClientActivity(UserClientActivity $userClientActivity): self
    {
        if (!$this->userClientActivities->contains($userClientActivity)) {
            $this->userClientActivities[] = $userClientActivity;
            $userClientActivity->setMission($this);
        }

        return $this;
    }

    public function removeUserClientActivity(UserClientActivity $userClientActivity): self
    {
        if ($this->userClientActivities->contains($userClientActivity)) {
            $this->userClientActivities->removeElement($userClientActivity);
            // set the owning side to null (unless already changed)
            if ($userClientActivity->getMission() === $this) {
                $userClientActivity->setMission(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|MissionMedia[]
     */
    public function getMissionMedia(): Collection
    {
        return $this->missionMedia;
    }

    public function addMissionMedium(MissionMedia $missionMedia): self
    {
        if (!$this->missionMedia->contains($missionMedia)) {
            $this->missionMedia[] = $missionMedia;
            $missionMedia->setMission($this);
        }

        return $this;
    }

    public function removeMissionMedium(MissionMedia $missionMedia): self
    {
        if ($this->missionMedia->contains($missionMedia)) {
            $this->missionMedia->removeElement($missionMedia);
            // set the owning side to null (unless already changed)
            if ($missionMedia->getMission() === $this) {
                $missionMedia->setMission(null);
            }
        }

        return $this;
    }
}
