<?php

namespace App\Entity;

use App\Constant\CardStatus;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 * @ORM\EntityListeners({"App\Entity\Listener\CardListener"})
 * @ORM\Table(name="pxl_card")
 */
class Card
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
     * @ORM\OneToOne(targetEntity="App\Entity\CardMedia", cascade={"persist"}, orphanRemoval=true)
     */
    private $thumb;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CardMedia", cascade={"persist"}, orphanRemoval=true)
     */
    private $masterhead;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $name = "";

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @Assert\Length(max=128)
     * @ORM\Column(length=128, unique=true)
     */
    private $slug = "";

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     */
    private $meta_title = "";

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     */
    private $meta_description = "";

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text", length=16777215)
     */
    private $content = "";

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $indexed = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="cards")
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cards")
     * @ORM\JoinColumn(name="pixie_id", referencedColumnName="id", nullable=false)
     */
    private $pixie;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CardCategory", inversedBy="cards")
     * @ORM\JoinTable(name="pxl_cards_categories",
     *      joinColumns={@ORM\JoinColumn(name="card_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardInfo", mappedBy="card", cascade={"persist"}, orphanRemoval=true)
     */
    private $infos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardMedia", mappedBy="card", cascade={"persist"}, orphanRemoval=true)
     */
    private $medias;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", cascade={"persist"}, orphanRemoval=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $shares = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $favorites = 0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CardProject", mappedBy="card", fetch="EXTRA_LAZY", orphanRemoval=true)
     */
    private $project;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin")
     */
    private $updatedBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="favorites")
     */
    private $favoriteUsers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="likes")
     */
    private $likeUsers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CardCollection", mappedBy="cards")
     */
    private $collections;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted = false;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CardDetailsApi", mappedBy="card", cascade={"persist", "remove"})
     */
    private $cardDetailsApi;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $gmbFlag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tagLine;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $igRedirectCount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;


    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------


    public function __construct()
    {
        $this->infos = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName();
    }

    public function addInfo(CardInfo $info)
    {
        $info->setCard($this);
        $this->infos[] = $info;
        return $this;
    }

    public function removeInfo(CardInfo $info)
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

    public function addMedia(CardMedia $media)
    {
        $media->setCard($this);
        $this->medias[] = $media;
        return $this;
    }

    public function removeMedia(CardMedia $media)
    {
        $this->medias->removeElement($media);
    }

    public function getMedias()
    {
        return $this->medias;
    }

    public function setMedias($medias)
    {
        $this->medias = $medias;
    }

    public function generateSlug()
    {
        $slug = $this->getName();
        if(!empty($this->getAddress()) && !empty($this->getAddress()->getCity())) $slug .= " ".$this->getAddress()->getCity();
        if(!empty($this->getDepartment())) $slug .= " ".$this->getDepartment();
        if(!empty($this->getRegion())) $slug .= " ".$this->getRegion();

        $this->setSlug($slug);

        return $slug;
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getStatusLabel()
    {
        return CardStatus::getLabel($this->status);
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title)
    {
        $this->meta_title = isset($meta_title)?$meta_title:"";
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description)
    {
        $this->meta_description = isset($meta_description)?$meta_description:"";
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getIndexed()
    {
        return $this->indexed;
    }

    /**
     * @param mixed $indexed
     */
    public function setIndexed($indexed)
    {
        $this->indexed = $indexed;
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
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @param mixed $likes
     */
    public function addLike()
    {
        $this->likes++;
    }

    /**
     * @param mixed $likes
     */
    public function removeLike()
    {
        if($this->likes > 0) $this->likes--;
    }

    /**
     * @return mixed
     */
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * @param mixed $shares
     */
    public function setShares($shares)
    {
        $this->shares = $shares;
    }

    /**
     * @param mixed $likes
     */
    public function addShare()
    {
        $this->shares++;
    }

    /**
     * @param mixed $likes
     */
    public function removeShare()
    {
        if($this->shares > 0) $this->shares--;
    }

    /**
     * @return mixed
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @param mixed $thumb
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * @param mixed $favorites
     */
    public function setFavorites($favorites)
    {
        $this->favorites = $favorites;
    }

    /**
     * @param mixed $likes
     */
    public function addFavorite()
    {
        $this->favorites++;
    }

    /**
     * @param mixed $likes
     */
    public function removeFavorite()
    {
        if($this->favorites > 0) $this->favorites--;
    }


    /**
     * @return mixed
     */
    public function getFavoriteUsers()
    {
        return $this->favoriteUsers;
    }

    /**
     * @param mixed $favoriteUsers
     */
    public function setFavoriteUsers($favoriteUsers)
    {
        $this->favoriteUsers = $favoriteUsers;
    }

    /**
     * @return mixed
     */
    public function isDeleted()
    {
        return $this->deleted === true;
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

    /**
     * @return mixed
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * @param mixed $collections
     */
    public function setCollections($collections)
    {
        $this->collections = $collections;
    }

    /**
     * @return mixed
     */
    public function getLikeUsers()
    {
        return $this->likeUsers;
    }

    /**
     * @param mixed $likeUsers
     */
    public function setLikeUsers($likeUsers)
    {
        $this->likeUsers = $likeUsers;
    }

    /**
     * @return mixed
     */
    public function getMasterhead()
    {
        return $this->masterhead;
    }

    /**
     * @param mixed $masterhead
     */
    public function setMasterhead($masterhead)
    {
        $this->masterhead = $masterhead;
    }

    public function getCardDetailsApi(): ?CardDetailsApi
    {
        return $this->cardDetailsApi;
    }

    public function setCardDetailsApi(CardDetailsApi $cardDetailsApi): self
    {
        $this->cardDetailsApi = $cardDetailsApi;

        // set the owning side of the relation if necessary
        if ($this !== $cardDetailsApi->getCard()) {
            $cardDetailsApi->setCard($this);
        }

        return $this;
    }

    public function getGmbFlag(): ?int
    {
        return $this->gmbFlag;
    }

    public function setGmbFlag(?int $gmbFlag): self
    {
        $this->gmbFlag = $gmbFlag;

        return $this;
    }

    public function getTagLine(): ?string
    {
        return $this->tagLine;
    }

    public function setTagLine(?string $tagLine): self
    {
        $this->tagLine = $tagLine;

        return $this;
    }

    public function getIgRedirectCount(): ?int
    {
        return $this->igRedirectCount;
    }

    public function setIgRedirectCount(?int $igRedirectCount): self
    {
        $this->igRedirectCount = $igRedirectCount;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
    
    public function postSave($event)
    {
      $cacheDriver = $this->getTable()->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE);
      $cacheDriver->deleteByPrefix('findCardBySlug_');
    }

}
