<?php

namespace App\Entity;

use App\Constant\ViewMode;
use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pxl_user", indexes={@Index(name="index_email", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\EntityListeners({"App\Entity\Listener\UserRegistrationListener"})
 * @UniqueEntity(
 *     fields={"email"},
 *     message="error.email.exist"
 * )
 */
class User implements UserInterface, EquatableInterface
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
     * @Assert\Regex("/^(\+?\d+){9,}$/")
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $phone;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date", nullable=false)
     */
    private $birthdate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     */
    private $birthLocation;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^(?:0[1-9]|[1-9]\d)\d{3}$/")
     * @ORM\Column(type="string", length=16)
     */
    private $currentLocation;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=10)
     */
    private $gender;

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
     * @Assert\Length(max=64)
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @SecurityAssert\UserPassword(
     *     message = "Mot de passe incorrect",
     *     groups = {"UserEdit"}
     * )
     */
    protected $oldPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resetPasswordToken = "";

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $resetPasswordTokenExpire;

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
     * @ORM\OneToOne(targetEntity="App\Entity\UserMedia", cascade={"persist"}, orphanRemoval=true)
     */
    private $avatar;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\CardCategory")
     * @ORM\JoinTable(name="pxl_users_cardcategories",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $favoriteCategories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="pxl_users_pixies",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pixie_id", referencedColumnName="id")}
     *      )
     */
    private $favoritePixies;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Card", inversedBy="favoriteUsers")
     * @ORM\JoinTable(name="pxl_users_favorites",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="card_id", referencedColumnName="id")}
     *      )
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Card", inversedBy="likeUsers")
     * @ORM\JoinTable(name="pxl_users_likes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="card_id", referencedColumnName="id")}
     *      )
     */
    private $likes;




    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardCollection", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     */
    private $collections;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserLink", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     */
    private $links;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPixie", cascade={"persist"}, orphanRemoval=true)
     */
    private $pixie;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $googleId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $registerToken = "";

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $googleCalendarToken = "";

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $viewMode = ""; // user or pixie

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserOptin", cascade={"persist"}, orphanRemoval=true)
     */
    private $optin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="pixie", orphanRemoval=true)
     */
    private $cards;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CardProject", mappedBy="pixie", orphanRemoval=true)
     */
    private $projects;

    /**
     * @ORM\Column(type="integer")
     */
    private $followed = 0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $igFlag = false;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserRegistrationCheck", mappedBy="user", cascade={"persist", "remove"})
     */
    private $userRegistrationCheck;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserInstagramDetailsApi", mappedBy="user", cascade={"persist", "remove"})
     */
    private $userInstagramDetailsApi;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserAvatar", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     */
    private $userAvatars;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMedia", mappedBy="user",cascade={"persist"})
     */
    private $userMedia;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OutboundAnalytics", mappedBy="cm", orphanRemoval=true)
     */
    private $outboundAnalytics;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OutboundAnalytics", mappedBy="endUser")
     */
    private $outboundAnalyticsUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InstagramTrends", mappedBy="user")
     */
    private $instagramTrends;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPacks", mappedBy="user", cascade={"persist", "remove"})
     */
    private $userPacks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMission", mappedBy="user", cascade={"persist", "remove"})
     */
    private $userMission;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MissionPayment", mappedBy="user", cascade={"persist", "remove"})
     */
    private $missionPayment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientMissionProposal", mappedBy="user")
     */
    private $missionProposalsToCityMaker;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserClientActivity", mappedBy="user")
     */
    private $userActivities;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Region")
     * @ORM\JoinTable(name="pxl_b2b_regions_users")
     */
        private $userRegion;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Pack", mappedBy="user")
         */
        private $packs;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\CommunityMedia", mappedBy="user",cascade={"persist"})
         */
        private $communityMedia;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill")
     * @ORM\JoinTable(name="pxl_b2b_skills_users",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="id")})
     */
    private $userSkills;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cm_upgrade_b2b_date;

    //--------------------------------------------------------------
    // Constructor
    //--------------------------------------------------------------


    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->collections = new ArrayCollection();
        $this->favoriteCategories = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->favoritePixies = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->userAvatars = new ArrayCollection();
        $this->userMedia = new ArrayCollection();
        $this->outboundAnalytics = new ArrayCollection();
        $this->outboundAnalyticsUser = new ArrayCollection();
        $this->instagramTrends = new ArrayCollection();
        $this->missionProposalsToCityMaker = new ArrayCollection();
        $this->userMission = new ArrayCollection();
        $this->userActivities = new ArrayCollection();
        $this->userRegion = new ArrayCollection();
        $this->packs = new ArrayCollection();
        $this->userSkills = new ArrayCollection();
        $this->communityMedia = new ArrayCollection();

    }

    public function __toString() {
        return $this->getFirstname()." ".$this->getLastname();
    }


    //--------------------------------------------------------------
    // Collections
    //--------------------------------------------------------------

    public function addLink(UserLink $link)
    {
        $link->setUser($this);
        $this->links[] = $link;
        return $this;
    }

    public function removeLink(UserLink $link)
    {
        $this->links->removeElement($link);
    }


    public function addCollection(CardCollection $collection)
    {
        $collection->setUser($this);
        $this->collections[] = $collection;
        return $this;
    }

    public function removeCollection(CardCollection $collection)
    {
        $this->collections->removeElement($collection);
    }


    public function addFavoritePixie(User $pixie)
    {
        if ($this->favoritePixies->contains($pixie)) return $this;
        $pixie->addFollowed(); // Increment pixie followed counter
        $this->favoritePixies[] = $pixie;
        return $this;
    }

    public function removeFavoritePixie(User $pixie)
    {
        $pixie->removeFollowed(); // Decrement pixie followed counter
        $this->favoritePixies->removeElement($pixie);
    }

    public function hasFavoritePixie(User $pixie){
        return $this->favoritePixies->contains($pixie);
    }


    public function addLike(Card $card)
    {
        if ($this->likes->contains($card)) return $this;
        $card->addLike(); // Increment card like counter
        $this->likes[] = $card;
        return $this;
    }

    public function removeLike(Card $card)
    {
        $card->removeLike(); // Decrement card like counter
        $this->likes->removeElement($card);
    }

    public function hasLike(Card $card){
        return $this->likes->contains($card);
    }


    public function addFavorite(Card $card)
    {
        if ($this->favorites->contains($card)) return $this;
        $card->addFavorite(); // Increment card favorite counter
        $this->favorites[] = $card;
        return $this;
    }

    public function removeFavorite(Card $card)
    {
        $card->removeFavorite(); // Decrement card favorite counter
        $this->favorites->removeElement($card);
    }

    public function hasFavorite(Card $card){
        return $this->favorites->contains($card);
    }

    //--------------------------------------------------------------
    // Bind email to the username
    //--------------------------------------------------------------

    public function getUsername(){
        return $this->email;
    }

    public function getInstagram(){
        $link = $this->links->filter(function(UserLink $link){
            return $link->getType() === "instagram" && !empty($link->getUrl());
        });
        $link = array_values($link->toArray());
        return (isset($link) && count($link) > 0)?$link[0]->getUrl():null;
    }

    //--------------------------------------------------------------
    // Serialize user infos
    //--------------------------------------------------------------

    public function eraseCredentials(){
    }

    public function getSalt()
    {
        return null;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->active,
            $this->deleted,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->active,
            $this->deleted,
            ) = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        $isEqual = count($this->getRoles()) == count($user->getRoles());

        if (!$isEqual) {
            return false;
        }

        foreach($this->getRoles() as $role) {
            $isEqual = $isEqual && in_array($role, $user->getRoles());
        }

        if($user->getDeleted() || !$user->isActive()) $isEqual = false;

        return $isEqual;
    }


    //--------------------------------------------------------------
    // User Roles
    //--------------------------------------------------------------

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function addRole($role): void
    {
        $this->roles[] = $role;
    }



    //--------------------------------------------------------------
    // Validation groups
    //--------------------------------------------------------------

    public static function determineValidationGroups(FormInterface $form){
        $data = $form->getData();

        $groups = [];

        if($form->getConfig()->getOption("type") === "edit"){
            if(!empty($data->getPassword())) {
                $groups[] = "UserEdit";
            }
        }

        if($data->getPassword() === ""){
            if(empty($data->getFacebookId()) && empty($data->getGoogleId())) {
                $groups[] = "UserCreation";
            }
        }

        if(in_array("ROLE_PIXIE", $data->getRoles())){
            $groups[] = "User";
            $groups[] = "UserPixie";
            $groups[] = "UserPixieBilling";

            if($form->getConfig()->getOption("type") !== "edit"){
                $groups[] = "UserPixieCreation";
            }
        }
        else{
            $groups[] = "User";
        }

        return $groups;
    }


    //--------------------------------------------------------------
    // Getters and setters
    //--------------------------------------------------------------

    public function getSlug(){
        return 'abc';
        $slugify = new Slugify();
        $region = (count($this->getPixie()->getRegions()) > 0)?$this->getPixie()->getRegions()[0]:"";
        return $slugify->slugify($this->firstname." ".$this->lastname." ".$region);
    }

    public function  getAge(){
        return $this->birthdate->diff(new DateTime('today'))->y;
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
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return mixed
     */
    public function getBirthLocation()
    {
        return $this->birthLocation;
    }

    /**
     * @param mixed $birthLocation
     */
    public function setBirthLocation($birthLocation)
    {
        $this->birthLocation = $birthLocation;
    }

    /**
     * @return mixed
     */
    public function getCurrentLocation()
    {
        return $this->currentLocation;
    }

    /**
     * @param mixed $currentLocation
     */
    public function setCurrentLocation($currentLocation)
    {
        $this->currentLocation = $currentLocation;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getAvatarUrl()
    {
        $fileName = ($this->getAvatar() instanceof UserMedia)?$this->getAvatar():null;
        return ($fileName)?$fileName:"/uploads/avatars/default-avatar.png";
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
     * @param mixed $favorites
     */
    public function setFavorites($favorites)
    {
        $this->favorites = $favorites;
    }

    /**
     * @return mixed
     */
    public function getFavoriteCategories()
    {
        return $this->favoriteCategories;
    }

    /**
     * @return mixed
     */
    public function getFavorites()
    {
        return $this->favorites;
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

    public function getLinks()
    {
        return $this->links->filter(function(UserLink $link){
            return !empty($link->getUrl());
        });
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }


    /**
     * @return mixed
     */
    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }

    /**
     * @param mixed $resetPasswordToken
     */
    public function setResetPasswordToken($resetPasswordToken)
    {
        $this->resetPasswordToken = isset($resetPasswordToken)?$resetPasswordToken:"";
    }

    /**
     * @return mixed
     */
    public function getResetPasswordTokenExpire()
    {
        return $this->resetPasswordTokenExpire;
    }

    /**
     * @param mixed $resetPasswordTokenExpire
     */
    public function setResetPasswordTokenExpire($resetPasswordTokenExpire)
    {
        $this->resetPasswordTokenExpire = $resetPasswordTokenExpire;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param mixed $active
     */
    public function isActive()
    {
        return $this->active === true;
    }

    /**
     * @return mixed
     */
    public function getRegisterToken()
    {
        return $this->registerToken;
    }

    /**
     * @param mixed $registerToken
     */
    public function setRegisterToken($registerToken)
    {
        $this->registerToken = $registerToken;
    }

    /**
     * @return mixed
     */
    public function getViewMode()
    {
        return (isset($this->viewMode) && $this->viewMode !== "")?$this->viewMode:ViewMode::USER;
    }

    /**
     * @param mixed $viewMode
     */
    public function setViewMode($viewMode)
    {
        $this->viewMode = isset($viewMode)?$viewMode:ViewMode::USER;
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getOptin()
    {
        return $this->optin;
    }

    /**
     * @param mixed $optin
     */
    public function setOptin($optin)
    {
        $this->optin = $optin;
    }

    /**
     * @return mixed
     */
    public function getFavoritePixies()
    {
        return $this->favoritePixies;
    }

    /**
     * @param mixed $favoritePixies
     */
    public function setFavoritePixies($favoritePixies)
    {
        $this->favoritePixies = $favoritePixies;
    }

    /**
     * @return mixed
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param mixed $cards
     */
    public function setCards($cards)
    {
        $this->cards = $cards;
    }

    /**
     * @return mixed
     */
    public function getFollowed()
    {
        return $this->followed;
    }

    /**
     * @param mixed $followed
     */
    public function setFollowed($followed)
    {
        $this->followed = $followed;
    }

    /**
     * @param mixed $likes
     */
    public function addFollowed()
    {
        $this->followed++;
    }

    /**
     * @param mixed $likes
     */
    public function removeFollowed()
    {
        if($this->followed > 0) $this->followed--;
    }

    /**
     * @return mixed
     */
    public function getGoogleCalendarToken()
    {
        return $this->googleCalendarToken;
    }

    /**
     * @param mixed $googleCalendarToken
     */
    public function setGoogleCalendarToken($googleCalendarToken)
    {
        $this->googleCalendarToken = $googleCalendarToken;
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
     * @param mixed $delete
     */
    public function setIgFlag($igFlag)
    {
        $this->igFlag = $igFlag;
    }
    /**
     * @return mixed
     */
    public function getIgFlat()
    {
        return $this->igFlag;
    }

    /**
     * @param mixed $delete
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return mixed
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param mixed $projects
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    public function getUserRegistrationCheck(): ?UserRegistrationCheck
    {
        return $this->userRegistrationCheck;
    }

    public function setUserRegistrationCheck(?UserRegistrationCheck $userRegistrationCheck): self
    {
        $this->userRegistrationCheck = $userRegistrationCheck;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $userRegistrationCheck === null ? null : $this;
        if ($newUser !== $userRegistrationCheck->getUser()) {
            $userRegistrationCheck->setUser($newUser);
        }

        return $this;
    }

    public function getUserInstagramDetailsApi(): ?UserInstagramDetailsApi
    {
        return $this->userInstagramDetailsApi;
    }

    public function setUserInstagramDetailsApi(UserInstagramDetailsApi $userInstagramDetailsApi): self
    {
        $this->userInstagramDetailsApi = $userInstagramDetailsApi;

        // set the owning side of the relation if necessary
        if ($this !== $userInstagramDetailsApi->getUser()) {
            $userInstagramDetailsApi->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserAvatar[]
     */
    public function getUserAvatars(): Collection
    {
        return $this->userAvatars;
    }

    public function addUserAvatar(UserAvatar $userAvatar): self
    {
        if (!$this->userAvatars->contains($userAvatar)) {
            $this->userAvatars[] = $userAvatar;
            $userAvatar->setUser($this);
        }

        return $this;
    }

    public function removeUserAvatar(UserAvatar $userAvatar): self
    {
        if ($this->userAvatars->contains($userAvatar)) {
            $this->userAvatars->removeElement($userAvatar);
            // set the owning side to null (unless already changed)
            if ($userAvatar->getUser() === $this) {
                $userAvatar->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserMedia[]
     */
    public function getUserMedia(): Collection
    {
        return $this->userMedia;
    }

    public function addUserMedium(UserMedia $userMedium): self
    {
        if (!$this->userMedia->contains($userMedium)) {
            $this->userMedia[] = $userMedium;
            $userMedium->setUser($this);
        }

        return $this;
    }

    public function removeUserMedium(UserMedia $userMedium): self
    {
        if ($this->userMedia->contains($userMedium)) {
            $this->userMedia->removeElement($userMedium);
            // set the owning side to null (unless already changed)
            if ($userMedium->getUser() === $this) {
                $userMedium->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OutboundAnalytics[]
     */
    public function getOutboundAnalytics(): Collection
    {
        return $this->outboundAnalytics;
    }

    public function addOutboundAnalytic(OutboundAnalytics $outboundAnalytic): self
    {
        if (!$this->outboundAnalytics->contains($outboundAnalytic)) {
            $this->outboundAnalytics[] = $outboundAnalytic;
            $outboundAnalytic->setCm($this);
        }

        return $this;
    }

    public function removeOutboundAnalytic(OutboundAnalytics $outboundAnalytic): self
    {
        if ($this->outboundAnalytics->contains($outboundAnalytic)) {
            $this->outboundAnalytics->removeElement($outboundAnalytic);
            // set the owning side to null (unless already changed)
            if ($outboundAnalytic->getCm() === $this) {
                $outboundAnalytic->setCm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OutboundAnalytics[]
     */
    public function getOutboundAnalyticsUser(): Collection
    {
        return $this->outboundAnalyticsUser;
    }

    public function addOutboundAnalyticsUser(OutboundAnalytics $outboundAnalyticsUser): self
    {
        if (!$this->outboundAnalyticsUser->contains($outboundAnalyticsUser)) {
            $this->outboundAnalyticsUser[] = $outboundAnalyticsUser;
            $outboundAnalyticsUser->setEndUser($this);
        }

        return $this;
    }

    public function removeOutboundAnalyticsUser(OutboundAnalytics $outboundAnalyticsUser): self
    {
        if ($this->outboundAnalyticsUser->contains($outboundAnalyticsUser)) {
            $this->outboundAnalyticsUser->removeElement($outboundAnalyticsUser);
            // set the owning side to null (unless already changed)
            if ($outboundAnalyticsUser->getEndUser() === $this) {
                $outboundAnalyticsUser->setEndUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InstagramTrends[]
     */
    public function getInstagramTrends(): Collection
    {
        return $this->instagramTrends;
    }

    public function addInstagramTrend(InstagramTrends $instagramTrend): self
    {
        if (!$this->instagramTrends->contains($instagramTrend)) {
            $this->instagramTrends[] = $instagramTrend;
            $instagramTrend->setUser($this);
        }

        return $this;
    }

    public function removeInstagramTrend(InstagramTrends $instagramTrend): self
    {
        if ($this->instagramTrends->contains($instagramTrend)) {
            $this->instagramTrends->removeElement($instagramTrend);
            // set the owning side to null (unless already changed)
            if ($instagramTrend->getUser() === $this) {
                $instagramTrend->setUser(null);
            }
        }

        return $this;
    }

    public function getUserPacks(): ?Collection
    {
        return $this->userPacks;
    }

    public function setUserPacks(UserPacks $userPacks): self
    {
        $this->userPacks = $userPacks;

        // set the owning side of the relation if necessary
        if ($this !== $userPacks->getUser()) {
            $userPacks->setUser($this);
        }

        return $this;
    }

    public function getUserMission(): ?Collection
    {
        return $this->userMission;
    }

//    public function setUserMission(?UserMission $userMission): self
//    {
//        $this->userMission = $userMission;
//
//        // set (or unset) the owning side of the relation if necessary
//        $newUser = $userMission === null ? null : $this;
//        if ($newUser !== $userMission->getUser()) {
//            $userMission->setUser($newUser);
//        }
//
//        return $this;
//    }

    public function addUserMission(UserMission $userMission): self
    {
        if (!$this->userMission->contains($userMission)) {
            $this->userMission[] = $userMission;
            $userMission->setUser($this);
        }

        return $this;
    }

    public function removeUserMission(UserMission $userMission): self
    {
        if ($this->userMission->contains($userMission)) {
            $this->userMission->removeElement($userMission);
            // set the owning side to null (unless already changed)
            if ($userMission->getUser() === $this) {
                $userMission->setUser(null);
            }
        }

        return $this;
    }

    public function getMissionPayment(): ?MissionPayment
    {
        return $this->missionPayment;
    }

    public function setMissionPayment(?MissionPayment $missionPayment): self
    {
        $this->missionPayment = $missionPayment;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $missionPayment === null ? null : $this;
        if ($newUser !== $missionPayment->getUser()) {
            $missionPayment->setUser($newUser);
        }

        return $this;
    }

    /**
     * @return Collection|ClientMissionProposal[]
     */
    public function getMissionProposalsToCityMaker(): Collection
    {
        return $this->missionProposalsToCityMaker;
    }

    public function addMissionProposalsToCityMaker(ClientMissionProposal $missionProposalsToCityMaker): self
    {
        if (!$this->missionProposalsToCityMaker->contains($missionProposalsToCityMaker)) {
            $this->missionProposalsToCityMaker[] = $missionProposalsToCityMaker;
            $missionProposalsToCityMaker->setUser($this);
        }

        return $this;
    }

    public function removeMissionProposalsToCityMaker(ClientMissionProposal $missionProposalsToCityMaker): self
    {
        if ($this->missionProposalsToCityMaker->contains($missionProposalsToCityMaker)) {
            $this->missionProposalsToCityMaker->removeElement($missionProposalsToCityMaker);
            // set the owning side to null (unless already changed)
            if ($missionProposalsToCityMaker->getUser() === $this) {
                $missionProposalsToCityMaker->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserClientActivity[]
     */
    public function getUserActivities(): Collection
    {
        return $this->userActivities;
    }

    public function addUserActivity(UserClientActivity $userActivity): self
    {
        if (!$this->userActivities->contains($userActivity)) {
            $this->userActivities[] = $userActivity;
            $userActivity->setUser($this);
        }

        return $this;
    }

    public function removeUserActivity(UserClientActivity $userActivity): self
    {
        if ($this->userActivities->contains($userActivity)) {
            $this->userActivities->removeElement($userActivity);
            // set the owning side to null (unless already changed)
            if ($userActivity->getUser() === $this) {
                $userActivity->setUser(null);
            }
        }

        return $this;
    }

    public function setUserRegion($region)
    {
        $this->userRegion = $region;
    }

    /**
     * @return mixed
     */
    public function getUserRegion()
    {
        return $this->userRegion;
    }

    /**
     * @return mixed
     */
    public function addUserRegion($region){
        $this->userRegion[] = $region;
    }




    /**
     * @return mixed
     */
    public function getUserSkill()
    {
        return $this->userSkills;
    }

    /**
     * @return mixed
     */
    public function addUserSkill($skill){
        $this->userSkills[] = $skill;
    }

    public function setUserSkill($skill)
    {
        $this->userSkills = $skill;
    }

    /**
     * @return Collection|CommunityMedia[]
     */
    public function getCommunityMedia(): Collection
    {
        return $this->communityMedia;
    }

    public function addCommunityMedium(CommunityMedia $communityMedium): self
    {
        if (!$this->communityMedia->contains($communityMedium)) {
            $this->communityMedia[] = $communityMedium;
            $communityMedium->setUser($this);
        }

        return $this;
    }

    public function removeCommunityMedium(CommunityMedia $communityMedium): self
    {
        if ($this->communityMedia->contains($communityMedium)) {
            $this->communityMedia->removeElement($communityMedium);
            // set the owning side to null (unless already changed)
            if ($communityMedium->getUser() === $this) {
                $communityMedium->setUser(null);
            }
        }

        return $this;
    }



    public function getCmUpgradeB2bDate(): ?\DateTimeInterface
    {
        return $this->cm_upgrade_b2b_date;
    }

    public function setCmUpgradeB2bDate(?\DateTimeInterface $cm_upgrade_b2b_date): self
    {
        $this->cm_upgrade_b2b_date = $cm_upgrade_b2b_date;

        return $this;
    }



}
