<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @ORM\EntityListeners({"App\Entity\Listener\ClientListener"})
 * @ORM\Table(name="pxl_b2b_client")
 * * @UniqueEntity(
 *     fields={"email"},
 *     message="error.email.exist"
 * )
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @Assert\NotBlank(groups = {"UserCreation"})
     * @Assert\Length(max=4096)
     * @Assert\Regex(
     *     pattern = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d$@$!%*#?&]{8,}$/",
     *     message = "error.password.format"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ClientInfo", mappedBy="client", cascade={"persist", "remove"})
     */
    private $clientInfo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMission", mappedBy="client", cascade={"persist", "remove"})
     */
    private $userMission;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientMissionProposal", mappedBy="client")
     */
    private $clientMissionProposals;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notifications", mappedBy="client")
     */
    private $notifications;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilePhoto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoggedinAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="client")
     */
    private $ticket;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientTransaction", mappedBy="user")
     */
    private $clientTransactions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedinId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookId;

    public function __construct()
    {
        $this->clientMissionProposals = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->ticket = new ArrayCollection();
        $this->clientTransactions = new ArrayCollection();
    }

    public function __toString() {
        return $this->getCompany();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

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

    public function getClientInfo(): ?ClientInfo
    {
        return $this->clientInfo;
    }

    public function setClientInfo(?ClientInfo $clientInfo): self
    {
        $this->clientInfo = $clientInfo;

        // set (or unset) the owning side of the relation if necessary
        $newClient = $clientInfo === null ? null : $this;
        if ($newClient !== $clientInfo->getClient()) {
            $clientInfo->setClient($newClient);
        }

        return $this;
    }

    public function getUserMission(): ?Collection
    {
        return $this->userMission;
    }

    public function setUserMission(?UserMission $userMission): self
    {
        $this->userMission = $userMission;

        // set (or unset) the owning side of the relation if necessary
        $newClient = $userMission === null ? null : $this;
        if ($newClient !== $userMission->getClient()) {
            $userMission->setClient($newClient);
        }

        return $this;
    }

    /**
     * @return Collection|ClientMissionProposal[]
     */
    public function getClientMissionProposals(): Collection
    {
        return $this->clientMissionProposals;
    }

    public function addClientMissionProposal(ClientMissionProposal $clientMissionProposal): self
    {
        if (!$this->clientMissionProposals->contains($clientMissionProposal)) {
            $this->clientMissionProposals[] = $clientMissionProposal;
            $clientMissionProposal->setClient($this);
        }

        return $this;
    }

    public function removeClientMissionProposal(ClientMissionProposal $clientMissionProposal): self
    {
        if ($this->clientMissionProposals->contains($clientMissionProposal)) {
            $this->clientMissionProposals->removeElement($clientMissionProposal);
            // set the owning side to null (unless already changed)
            if ($clientMissionProposal->getClient() === $this) {
                $clientMissionProposal->setClient(null);
            }
        }

        return $this;
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
            $notification->setClient($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getClient() === $this) {
                $notification->setClient(null);
            }
        }

        return $this;
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

    public function getLastLoggedinAt(): ?\DateTimeInterface
    {
        return $this->lastLoggedinAt;
    }

    public function setLastLoggedinAt(\DateTimeInterface $lastLoggedinAt): self
    {
        $this->lastLoggedinAt = $lastLoggedinAt;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTicket(): Collection
    {
        return $this->ticket;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->ticket->contains($ticket)) {
            $this->ticket[] = $ticket;
            $ticket->setClient($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->ticket->contains($ticket)) {
            $this->ticket->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getClient() === $this) {
                $ticket->setClient(null);
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
            $clientTransaction->setUser($this);
        }

        return $this;
    }

    public function removeClientTransaction(ClientTransaction $clientTransaction): self
    {
        if ($this->clientTransactions->contains($clientTransaction)) {
            $this->clientTransactions->removeElement($clientTransaction);
            // set the owning side to null (unless already changed)
            if ($clientTransaction->getUser() === $this) {
                $clientTransaction->setUser(null);
            }
        }

        return $this;
    }


    public function getEncryptedId(){

        return base64_encode($this->getId());
    }

    public function getLinkedinId(): ?string
    {
        return $this->linkedinId;
    }

    public function setLinkedinId(?string $linkedinId): self
    {
        $this->linkedinId = $linkedinId;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function getLogo()
    {
        $fileName = ($this->getProfilePhoto() != '')?'/uploads/clients/'.$this->getId().'/'.$this->getProfilePhoto():null;
        return ($fileName)?$fileName:"/assets/images/login-reg-place.svg";
    }


}
