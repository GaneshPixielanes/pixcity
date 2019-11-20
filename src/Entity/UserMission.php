<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserMissionRepository")
 * @ORM\EntityListeners({"App\Entity\Listener\MissionListener"})
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="userMission", cascade={"persist"},fetch="EAGER")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserPacks", cascade={"persist", "remove"},fetch="EAGER")
     */
    private $referencePack;

    /**
     * @ORM\Column(type="text", length=650000, nullable=true)
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientTransaction", mappedBy="mission")
     */
    private $clientTransactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MissionDocument", mappedBy="mission",cascade={"persist"})
     */
    private $documents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cancelReason;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $cancelledBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MissionLog", mappedBy="mission",cascade={"persist"})
     */
    private $missionLogs;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\missionLog")
     */
    private $log;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Royalties", mappedBy="mission", cascade={"persist", "remove"})
     */
    private $royalties;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Region")
     * @ORM\JoinTable(name="pxl_b2b_mission_regions",
     *      joinColumns={@ORM\JoinColumn(name="mission_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="region_id", referencedColumnName="id")})
     */
    private $missionRegions;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $isTvaApplicable;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MissionRecurringPriceLog", mappedBy="mission",cascade={"persist"})
     */
    private $active_log;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private $missionType;


    public function __construct()
    {
        $this->userClientActivities = new ArrayCollection();
        $this->missionMedia = new ArrayCollection();
        $this->clientTransactions = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->missionLogs = new ArrayCollection();
        $this->missionRegions = new ArrayCollection();
        $this->active_log = new ArrayCollection();
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
        return "uploads/".UserMission::UPLOAD_FOLDER.'/'.$this->getId()."/";
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
    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }


    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

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

    /**
     * @return Collection|ClientTransaction[]
     */
    public function getClientTransactions(): Collection
    {
        return $this->clientTransactions;
    }

    public function addClientTransaction(ClientTransaction $clientTransaction): self
    {
        if (!$this->clientTransactions->contains($clientTransaction)) {
            $this->clientTransactions[] = $clientTransaction;
            $clientTransaction->setMissionRequest($this);
        }

        return $this;
    }

    public function removeClientTransaction(ClientTransaction $clientTransaction): self
    {
        if ($this->clientTransactions->contains($clientTransaction)) {
            $this->clientTransactions->removeElement($clientTransaction);
            // set the owning side to null (unless already changed)
            if ($clientTransaction->getMissionRequest() === $this) {
                $clientTransaction->setMissionRequest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MissionDocument[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(MissionDocument $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setMission($this);
        }

        return $this;
    }

    public function removeDocument(MissionDocument $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getMission() === $this) {
                $document->setMission(null);
            }
        }

        return $this;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(?string $cancelReason): self
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }

    public function getCancelledBy(): ?int
    {
        return $this->cancelledBy;
    }

    public function setCancelledBy(?int $cancelledBy): self
    {
        $this->cancelledBy = $cancelledBy;

        return $this;
    }

    /**
     * @return Collection|MissionLog[]
     */
    public function getMissionLogs(): Collection
    {
        return $this->missionLogs;
    }

    /**

     * @return Collection|MissionRecurringPriceLog[]
     */
    public function getMissionPriceLogs(): Collection
    {
        return $this->active_log;
    }


    public function addMissionLog(MissionLog $missionLog): self
    {
        if (!$this->missionLogs->contains($missionLog)) {
            $this->missionLogs[] = $missionLog;
            $missionLog->setMission($this);
        }

        return $this;
    }

    public function removeMissionLog(MissionLog $missionLog): self
    {
        if ($this->missionLogs->contains($missionLog)) {
            $this->missionLogs->removeElement($missionLog);
            // set the owning side to null (unless already changed)
            if ($missionLog->getMission() === $this) {
                $missionLog->setMission(null);
            }
        }

        return $this;
    }

    public function getLog(): ?missionLog
    {
        return $this->log;
    }

    public function setLog(?missionLog $log): self
    {
        $this->log = $log;

        return $this;
    }

    public function getActiveLog()
    {
        $log = $this->missionLogs->filter(function (MissionLog $logs){
            return $logs->getIsActive() == 1;
        });
        $log = array_values($log->toArray());
        return (isset($log) && count($log) > 0)?$log[0]:null;
    }

    public function getActiveLogById(MissionLog $logs)
    {
        return $logs;
    }

    public function getRoyalties(): ?Royalties
    {
        return $this->royalties;
    }

    public function setRoyalties(Royalties $royalties): self
    {
        $this->royalties = $royalties;

        // set the owning side of the relation if necessary
        if ($this !== $royalties->getMission()) {
            $royalties->setMission($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMissionRegions()
    {
        return $this->missionRegions;
    }

    public function addMissionRegions(Region $region)
    {
        $this->missionRegions[] = $region;
    }

    /**
     * @param mixed $missionRegions
     */
    public function setMissionRegions($missionRegions): void
    {
        $this->missionRegions = $missionRegions;
    }

    public function getQuatationPdf(){

        return base64_encode("uploads/missions/temp/".$this->getId().'/'.$this->getMissionLogs()->last()->getQuotationfile());
    }

    public function getClientInvoice(){

        return base64_encode("invoices/".$this->getId().'/PX-'.$this->getId().'-'.$this->getActiveLog()->getId().'-client.pdf');
    }

    public function getCityMakerInvoice(){

        return base64_encode("invoices/".$this->getId().'/PX-'.$this->getId().'-'.$this->getActiveLog()->getId().'-cm.pdf');
    }

    public function getPcsInvoice(){
        return base64_encode("invoices/".$this->getId().'/PX-'.$this->getId().'-'.$this->getActiveLog()->getId().'-pcs.pdf');
    }

    public function getIsTvaApplicable(): ?string
    {
        return $this->isTvaApplicable;
    }

    public function setIsTvaApplicable(?string $isTvaApplicable): self
    {
        $this->isTvaApplicable = $isTvaApplicable;

        return $this;
    }

    public function addActiveLog(MissionRecurringPriceLog $activeLog): self
    {
        if (!$this->active_log->contains($activeLog)) {
            $this->active_log[] = $activeLog;
            $activeLog->setMission($this);
        }

        return $this;
    }

    public function removeActiveLog(MissionRecurringPriceLog $activeLog): self
    {
        if ($this->active_log->contains($activeLog)) {
            $this->active_log->removeElement($activeLog);
            // set the owning side to null (unless already changed)
            if ($activeLog->getMission() === $this) {
                $activeLog->setMission(null);
            }
        }

        return $this;
    }

    public function getMissionType(): ?string
    {
        return $this->missionType;
    }

    public function setMissionType(?string $missionType): self
    {
        $this->missionType = $missionType;

        return $this;
    }


}
