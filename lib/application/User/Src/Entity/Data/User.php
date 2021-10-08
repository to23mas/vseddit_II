<?php declare(strict_types = 1);

namespace Tom\Application\User\Entity\Data;



use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Nettrine\ORM\Entity;
use Tom\Application\Comment\Entity\Data\Comment;
use Tom\Application\Post\Entity\Data\Post;
use Tom\Application\SubForum\Entity\Data\SubForum;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(name="user_id", type="integer")
     */
    protected int $userId;

    /**
     * @var string
     * @ORM\Column(name="user_nick", type="string")
     */
    private string $userNick;

    /**
     * @var string
     * @ORM\Column(name="user_password", type="string")
     */
    private string $userPassword;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tom\Application\SubForum\Entity\Data\SubForum", mappedBy="creator", cascade={"persist", "remove"})
     */
    private Collection $mySubForums;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tom\Application\Post\Entity\Data\Post", mappedBy="user", cascade={"persist", "remove"})
     */
    private Collection $myPosts;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tom\Application\Comment\Entity\Data\Comment", mappedBy="creator", cascade={"persist", "remove"})
     */
    private Collection $myComments;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Tom\Application\SubForum\Entity\Data\SubForum", inversedBy="subscribers", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="subscriptions",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="sub_id", referencedColumnName="sub_id")
     *     }
     * )
     */
    private Collection $subscriptions;

    /**
     * @param string $userNick
     * @param string $userPassword
     */
    public function __construct(string $userNick, string $userPassword)
    {
        $this->userNick = $userNick;
        $this->userPassword = $userPassword;
        $this->mySubForums = new ArrayCollection();
        $this->myPosts = new ArrayCollection();
        $this->myComments = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUserNick(): string
    {
        return $this->userNick;
    }

    /**
     * @param string $userNick
     */
    public function setUserNick(string $userNick): void
    {
        $this->userNick = $userNick;
    }


    /**
     * @return string
     */
    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    /**
     * @param string $userPassword
     */
    public function setUserPassword(string $userPassword): void
    {
        $this->userPassword = $userPassword;
    }

    /**
     * @return Collection
     */
    public function getMySubForums(): Collection
    {
        return $this->mySubForums;
    }

    /**
     * @param Collection $mySubForums
     */
    public function setMySubForums(Collection $mySubForums): void
    {
        $this->mySubForums = $mySubForums;
    }

    /**
     * @return Collection
     */
    public function getMyPosts() : Collection
    {
        return $this->myPosts;
    }

    /**
     * @param Collection $myPosts
     */
    public function setMyPosts(Collection $myPosts): void
    {
        $this->myPosts = $myPosts;
    }

    /**
     * @param SubForum $subForum
     * @return $this
     */
    public function addSub(SubForum $subForum) : self
    {
        $this->mySubForums[] = $subForum;
        return $this;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function addPost(Post $post) : self
    {
        $this->myPosts[] = $post;
        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment) : self
    {
        $this->myComments[] = $comment;
        return $this;
    }

    /**
     * @param Post $post
     * @return bool
     */
    public function removePost(Post $post) : bool
    {
        return $this->myPosts->removeElement($post);
    }

    /**
     * @param SubForum $subForum
     * @return bool
     */
    public function removeSub(SubForum $subForum) : bool
    {
        return $this->mySubForums->removeElement($subForum);
    }

    /**
     * @param Comment $comment
     * @return bool
     */
    public function removeComment(Comment $comment) : bool
    {
        return $this->myComments->removeElement($comment);
    }

    /**
     * @return Collection
     */
    public function getMyComments(): Collection
    {
        return $this->myComments;
    }

    /**
     * @param Collection $myComments
     */
    public function setMyComments(Collection $myComments): void
    {
        $this->myComments = $myComments;
    }

    /**
     * @return Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param Collection $subscriptions
     */
    public function setSubscriptions(Collection $subscriptions): void
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @param SubForum $subForum
     * @return $this
     */
    public function addSubscription(SubForum $subForum) : self
    {
         $this->subscriptions[] = $subForum;
         return $this;
    }

    public function removeSubscription(SubForum $subForum) : bool
    {
        return $this->subscriptions->removeElement($subForum);
    }

    /** ---------------------------------------------------------- */


    /**
     * Vypíše jméno uživatele
     * @return string
     */
    public function __toString()  : string
    {
        return  $this->userNick;
    }
}