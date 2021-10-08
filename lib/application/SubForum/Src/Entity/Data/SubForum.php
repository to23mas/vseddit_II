<?php declare(strict_types=1);

namespace Tom\Application\SubForum\Entity\Data;

use Tom\Application\Post\Entity\Data\Post;
use Tom\Application\User\Entity\Data\User;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Nettrine\ORM\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="sub_forum")
 */
class SubForum
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sub_id", type="integer")
     */
    protected int $subId;

    /**
     * @var string
     * @ORM\Column(name="sub_title", type="string")
     */
    private string $subTitle;

    /**
     * @var string
     * @ORM\Column(name="sub_description", type="string")
     */
    private string $subDescription;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tom\Application\Post\Entity\Data\Post", mappedBy="subForum", cascade={"persist", "remove"})
     */
    private Collection $myPosts;



    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Tom\Application\User\Entity\Data\User", mappedBy="subscriptions")
     */
    private Collection $subscribers;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Tom\Application\User\Entity\Data\User", inversedBy="mySubForums", cascade={"persist"})
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="user_id", nullable=false)
     */
    private User $creator;


    /**
     * @param string $subTitle
     * @param string $subDescription
     */
    public function __construct(string $subTitle, string $subDescription)
    {
        $this->subTitle = $subTitle;
        $this->subDescription = $subDescription;
        $this->myPosts = new ArrayCollection();
        $this->subscribers = new ArrayCollection();
    }



    /**
     * @return string
     */
    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     */
    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    /**
     * @return string
     */
    public function getSubDescription(): string
    {
        return $this->subDescription;
    }

    /**
     * @param string $subDescription
     */
    public function setSubDescription(string $subDescription): void
    {
        $this->subDescription = $subDescription;
    }

    /**
     * @return int
     */
    public function getSubId(): int
    {
        return $this->subId;
    }

    /**
     * @return User
     */
    public function getCreator(): user
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return Collection
     */
    public function getMyPosts(): Collection
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
     * @param Post $post
     * @return $this
     */
    public function addPost(Post $post) : self
    {
        $this->myPosts[] = $post;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getSubscribers() : Collection
    {
        return $this->subscribers;
    }

    /**
     * @param Collection $subscribers
     */
    public function setSubscribers(Collection $subscribers): void
    {
        $this->subscribers = $subscribers;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addSubscriber(User $user) : self
    {
        $this->subscribers[] = $user;
        return $this;
    }

    public function removeSubscriber(User $user) : bool
    {
        return $this->subscribers->removeElement($user);
    }
}
