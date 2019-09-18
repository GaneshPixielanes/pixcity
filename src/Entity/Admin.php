<?php

namespace App\Entity;
use App\Constant\ViewMode;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_admin")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="error.email.exist"
 * )
 */
class Admin implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $firstname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $lastname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilePhoto;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $linkedinProfile;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

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
     * @ORM\OneToMany(targetEntity="App\Entity\Notifications", mappedBy="sentFrom")
     */
    private $notifications;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Config", mappedBy="createdBy", cascade={"persist", "remove"})
     */
    private $config;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Config", mappedBy="updatedBy", cascade={"persist", "remove"})
     */
    private $configUpdatedBy;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $viewMode = "";
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogCategory", mappedBy="definedBy")
     */
    private $blogCategorys;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="createdBy")
     */
    private $blogPosts;


    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->packs = new ArrayCollection();
        $this->blogCategorys = new ArrayCollection();
        $this->blogPosts = new ArrayCollection();
    }
    public function __toString() {
        return $this->getFirstname()." ".$this->getLastname();
    }




    public function getUsername(){
        return $this->email;
    }



    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getHighestRole()
    {
        $rolesSortedByImportance = ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_MODERATOR', 'ROLE_USER'];
        foreach ($rolesSortedByImportance as $role)
        {
            if (in_array($role, $this->getRoles()))
            {
                return $role;
            }
        }

        return false; // Unknown role?
    }


    public function eraseCredentials(){
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getProfilePhoto(): ?string
    {
        return $this->profilePhoto;
    }

    public function setProfilePhoto(?string $profilePhoto): self
    {
        $this->profilePhoto = $profilePhoto;

        return $this;
    }

    public function getLinkedinProfile(): ?string
    {
        return $this->linkedinProfile;
    }

    public function setLinkedinProfile(?string $linkedinProfile): self
    {
        $this->linkedinProfile = $linkedinProfile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setSentFrom($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getSentFrom() === $this) {
                $notification->setSentFrom(null);
            }
        }

        return $this;
    }

    public function getConfig(): ?Config
    {
        return $this->config;
    }

    public function setConfig(?Config $config): self
    {
        $this->config = $config;

        // set (or unset) the owning side of the relation if necessary
        $newCreatedBy = $config === null ? null : $this;
        if ($newCreatedBy !== $config->getCreatedBy()) {
            $config->setCreatedBy($newCreatedBy);
        }

        return $this;
    }

    public function getConfigUpdatedBy(): ?Config
    {
        return $this->configUpdatedBy;
    }

    public function setConfigUpdatedBy(?Config $configUpdatedBy): self
    {
        $this->configUpdatedBy = $configUpdatedBy;

        // set (or unset) the owning side of the relation if necessary
        $newUpdatedBy = $configUpdatedBy === null ? null : $this;
        if ($newUpdatedBy !== $configUpdatedBy->getUpdatedBy()) {
            $configUpdatedBy->setUpdatedBy($newUpdatedBy);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getViewMode()
    {
        return (isset($this->viewMode) && $this->viewMode !== "")?$this->viewMode:ViewMode::B2C;
    }

    /**
     * @param mixed $viewMode
     */
    public function setViewMode($viewMode)
    {
        $this->viewMode = isset($viewMode)?$viewMode:ViewMode::B2C;
    }
    /**
     * @return Collection|BlogPost[]
     */
    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogPost $blogPost): self
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts[] = $blogPost;
            $blogPost->setCreatedBy($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): self
    {
        if ($this->blogPosts->contains($blogPost)) {
            $this->blogPosts->removeElement($blogPost);
            // set the owning side to null (unless already changed)
            if ($blogPost->getCreatedBy() === $this) {
                $blogPost->setCreatedBy(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|BlogCategory[]
     */
    public function getBlogCategorys(): Collection
    {
        return $this->blogCategorys;
    }

    public function addBlogCategory(BlogCategory $blogCategory): self
    {
        if (!$this->blogCategorys->contains($blogCategory)) {
            $this->blogCategorys[] = $blogCategory;
            $blogCategory->setDefinedBy($this);
        }

        return $this;
    }

    public function removeBlogCategory(BlogCategory $blogCategory): self
    {
        if ($this->blogCategorys->contains($blogCategory)) {
            $this->blogCategorys->removeElement($blogCategory);
            // set the owning side to null (unless already changed)
            if ($blogCategory->getDefinedBy() === $this) {
                $blogCategory->getDefinedBy(null);
            }
        }

        return $this;
    }
}
