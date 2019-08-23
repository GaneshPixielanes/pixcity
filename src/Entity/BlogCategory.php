<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="pxl_blog_category")
 * @ORM\Entity(repositoryClass="App\Repository\BlogCategoryRepository")
 */
class BlogCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="blogCategorys")
     */
    private $definedBy;

    /**
     * @var \DateTime $createdAt
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="category")
     */
    private $postCategory;
    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $deleted;

    public function __construct()
    {
        $this->postCategory = new ArrayCollection();
    }
    public function __toString() {
        return $this->getTitle();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDefinedBy(): ?Admin
    {
        return $this->definedBy;
    }

    public function setDefinedBy(?Admin $definedBy): self
    {
        $this->definedBy = $definedBy;

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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getPostCategory(): Collection
    {
        return $this->postCategory;
    }

    public function addPostCategory(BlogPost $postCategory): self
    {
        if (!$this->postCategory->contains($postCategory)) {
            $this->postCategory[] = $postCategory;
            $postCategory->setCategory($this);
        }

        return $this;
    }

    public function removePostCategory(BlogPost $postCategory): self
    {
        if ($this->postCategory->contains($postCategory)) {
            $this->postCategory->removeElement($postCategory);
            // set the owning side to null (unless already changed)
            if ($postCategory->getCategory() === $this) {
                $postCategory->setCategory(null);
            }
        }

        return $this;
    }


    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function setDeleted(?int $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
