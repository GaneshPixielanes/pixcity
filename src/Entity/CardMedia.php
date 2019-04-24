<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\EntityListeners({"App\Entity\Listener\CardMediaListener"})
 * @ORM\Table(name="pxl_card_media")
 */
class CardMedia
{
    const UPLOAD_FOLDER = "cards";

    public static function uploadFolder(){
        $now = new \DateTime('now');
        return CardMedia::UPLOAD_FOLDER."/".$now->format("Y/m");
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $name = "";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Card", inversedBy="medias")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $card;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hashtag", mappedBy="media", cascade={"persist", "remove"})
     */
    private $hashtags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->hashtags = new ArrayCollection();
    }


    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------


    protected function datePath(){
        return (!empty($this->getUpdatedAt()))?"/".$this->getUpdatedAt()->format("Y/m"):"";
    }

    public function getUrl(){
        return "/uploads/".CardMedia::UPLOAD_FOLDER.$this->datePath()."/".$this->getName();
    }

    public function getRelativeUrl(){
        return "uploads/".CardMedia::UPLOAD_FOLDER.$this->datePath()."/".$this->getName();
    }

    public function getType(){
        if (file_exists($this->getRelativeUrl())) {
            $mimeType = mime_content_type($this->getRelativeUrl());
            switch ($mimeType) {
                case "image/jpeg":
                case "image/png":
                    $type = "image";
                    break;
                default:
                    $type = "file";
            }
        }
        else{
            $type = null;
        }
        return $type;
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
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param mixed $card
     */
    public function setCard($card = null)
    {
        $this->card = $card;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return isset($this->updatedAt)?$this->updatedAt:new \DateTime();
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Collection|Hashtag[]
     */
    public function getHashtags(): Collection
    {
        return $this->hashtags;
    }

    public function addHashtag(Hashtag $hashtag): self
    {
        if (!$this->hashtags->contains($hashtag)) {
            $this->hashtags[] = $hashtag;
            $hashtag->setMedia($this);
        }

        return $this;
    }

    public function removeHashtag(Hashtag $hashtag): self
    {
        if ($this->hashtags->contains($hashtag)) {
            $this->hashtags->removeElement($hashtag);
            // set the owning side to null (unless already changed)
            if ($hashtag->getMedia() === $this) {
                $hashtag->setMedia(null);
            }
        }

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

}
