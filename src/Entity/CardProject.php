<?php

namespace App\Entity;

use App\Constant\CardProjectStatus;
use App\Constant\PaymentStatus;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CardProjectRepository")
 * @ORM\EntityListeners({"App\Entity\Listener\CardProjectListener"})
 * @ORM\Table(name="pxl_card_project")
 */
class CardProject
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $name = "";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     */
    private $pixie;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text", length=65535)
     */
    private $description = "";

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CardCategory")
     * @ORM\JoinTable(name="pxl_cards_projects_categories",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardProjectInfo", mappedBy="project", cascade={"persist"}, orphanRemoval=true)
     */
    private $infos;

    /**
     * @ORM\Column(type="integer")
     */
    private $minPhotos = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $minVideos = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $minWords = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     */
    private $deliveryDate;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $comment = "";

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardProjectAttachment", mappedBy="project", cascade={"persist"}, orphanRemoval=true)
     */
    private $attachments;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Card", orphanRemoval=false, inversedBy="project")
     */
    private $card;

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
     * @var string $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin")
     */
    private $createdBy;

    /**
     * @var string $updatedBy
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin")
     */
    private $updatedBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Transaction", mappedBy="projects")
     */
    private $transactions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reminderEmailSent = false;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $contract = "";

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contract_details;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ContentDraft", mappedBy="projectID", cascade={"persist"})
     */
    private $contentDraft;

    //--------------------------------------------------------------
    // Validation groups
    //--------------------------------------------------------------

    public static function determineValidationGroups(FormInterface $form){
        $data = $form->getData();

        $groups = [];

        if($data->getStatus() !== CardProjectStatus::TEMPLATE){
            $groups[] = "assignation";
        }

        return $groups;
    }


    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------


    public function __construct()
    {
        $this->deliveryDate = new DateTime();
        $this->comment = "";
        $this->infos = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName();
    }

    public function addInfo(CardProjectInfo $info)
    {
        $info->setProject($this);
        $this->infos[] = $info;
        return $this;
    }

    public function removeInfo(CardProjectInfo $info)
    {
        $this->infos->removeElement($info);
    }

    public function getInfos()
    {
        return $this->infos;
    }

    public function setInfos($infos)
    {
        $this->infos = $infos;
    }

    public function addAttachment(CardProjectAttachment $attachment)
    {
        $attachment->setProject($this);
        $this->attachments[] = $attachment;
        return $this;
    }

    public function removeAttachment(CardProjectAttachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }


    public function isLate(){
        return ($this->getDeliveryDate() < new DateTime() && $this->getStatus() !== CardProjectStatus::VALIDATED && $this->getStatus() !== CardProjectStatus::REFUSED && $this->getStatus() !== CardProjectStatus::PIXIE_REFUSED);
    }


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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getStatusLabel()
    {
        return CardProjectStatus::getLabel($this->status);
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
    public function getPixie()
    {
        return $this->pixie;
    }

    /**
     * @param mixed $pixie
     */
    public function setPixie($pixie)
    {
        $this->pixie = $pixie;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = isset($description)?$description:"";
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed
     */
    public function getMinPhotos()
    {
        return $this->minPhotos;
    }

    /**
     * @param mixed $minPhotos
     */
    public function setMinPhotos($minPhotos)
    {
        $this->minPhotos = $minPhotos;
    }

    /**
     * @return mixed
     */
    public function getMinVideos()
    {
        return $this->minVideos;
    }

    /**
     * @param mixed $minVideos
     */
    public function setMinVideos($minVideos)
    {
        $this->minVideos = isset($minVideos)?$minVideos:0;
    }

    /**
     * @return mixed
     */
    public function getMinWords()
    {
        return $this->minWords;
    }

    /**
     * @param mixed $minWords
     */
    public function setMinWords($minWords)
    {
        $this->minWords = $minWords;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param mixed $deliveryDate
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = isset($comment)?$comment:"";
    }


    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param string $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param mixed $transactions
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @return mixed
     */
    public function getReminderEmailSent()
    {
        return $this->reminderEmailSent;
    }

    /**
     * @param mixed $reminderEmailSent
     */
    public function setReminderEmailSent($reminderEmailSent)
    {
        $this->reminderEmailSent = $reminderEmailSent;
    }

    /**
     * @return mixed
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param mixed $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
    }

    public function getContractDetails(): ?string
    {
        return $this->contract_details;
    }

    public function setContractDetails(?string $contract_details): self
    {
        $this->contract_details = $contract_details;

        return $this;
    }

    public function getContentDraft(): ?ContentDraft
    {
        return $this->contentDraft;
    }

    public function setContentDraft(?ContentDraft $contentDraft): self
    {
        $this->contentDraft = $contentDraft;

        // set (or unset) the owning side of the relation if necessary
        $newProjectID = $contentDraft === null ? null : $this;
        if ($newProjectID !== $contentDraft->getProjectID()) {
            $contentDraft->setProjectID($newProjectID);
        }

        return $this;
    }

}
