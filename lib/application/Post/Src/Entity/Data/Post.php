<?php declare(strict_types = 1);

namespace Tom\Application\Post\Entity\Data;

use Nette;

use Tom\Application\Comment\Entity\Data\Comment;
use Tom\Application\SubForum\Entity\Data\SubForum;
use Tom\Application\User\Entity\Data\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


use Nettrine\ORM\Entity;


/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 */
class Post{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(name="post_id", type="integer")
     */
    protected int $postId;

    /**
     * @var string
     * @ORM\Column(name="post_title", type="string")
     */
    private string $postTitle;

    /**
     * @var string
     * @ORM\Column(name="post_text", type="string")
     */
    private $postText;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Tom\Application\User\Entity\Data\User", inversedBy="myPosts", cascade={"persist"})
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="user_id", nullable=false)
     */
    private User $creator;

    /**
     * @var SubForum
     * @ORM\ManyToOne(targetEntity="Tom\Application\SubForum\Entity\Data\SubForum", inversedBy="myPosts", cascade={"persist"})
     * @ORM\JoinColumn(name="sub_id", referencedColumnName="sub_id", nullable=false)
     */
    private SubForum $subForum;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tom\Application\Comment\Entity\Data\Comment", mappedBy="myPost", cascade={"persist", "remove"})
     */
    private Collection $myComments;

    /**
     * @param string $postTitle
     * @param string $postText
     */
    public function __construct(string $postTitle, string $postText)
    {
        $this->postTitle = $postTitle;
        $this->postText = $postText;
        $this->myComments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }



    /**
     * @param int $postId
     */
    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    /**
     * @return string
     */
    public function getPostTitle(): string
    {
        return $this->postTitle;
    }

    /**
     * @param string $postTitle
     */
    public function setPostTitle(string $postTitle): void
    {
        $this->postTitle = $postTitle;
    }

    /**
     * @return string
     */
    public function getPostText(): string
    {
        return $this->postText;
    }

    /**
     * @param string $postText
     */
    public function setPostText(string $postText): void
    {
        $this->postText = $postText;
    }

    /**
     * @return User
     */
    public function getCreator() : User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return SubForum
     */
    public function getSubForum() : SubForum
    {
        return $this->subForum;
    }

    /**
     * @param SubForum $subForum
     */
    public function setSubForum(SubForum $subForum): void
    {
        $this->subForum = $subForum;
    }

    /**
     * @return Collection
     */
    public function getMyComments() : Collection
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
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment) : self
    {
        $this->myComments[] = $comment;
        return $this;
    }

    /**
     * @param Comment $comment
     * @return bool
     */
    public function removeComment(Comment $comment): bool
    {
        return $this->myComments->removeElement($comment);
    }



}