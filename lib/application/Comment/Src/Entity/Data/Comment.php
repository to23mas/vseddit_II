<?php declare(strict_types = 1);

namespace Tom\Application\Comment\Entity\Data;


use Tom\Application\User\Entity\Data\User;
use Tom\Application\Post\Entity\Data\Post;

use Doctrine\ORM\Mapping as ORM;

use Nettrine\ORM\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(name="com_id", type="integer")
     */
    protected int $comId;

    /**
     * @var string
     * @ORM\Column(name="com_text", type="string")
     */
    private string $comText;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Tom\Application\User\Entity\Data\User", inversedBy="myComments", cascade={"persist"})
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="user_id", nullable=false)
     */
    private User $creator;

    /**
     * @var Post
     * @ORM\ManyToOne(targetEntity="Tom\Application\Post\Entity\Data\Post", inversedBy="myComments", cascade={"persist"})
     * @ORM\JoinColumn(name="post_id", referencedColumnName="post_id", nullable=false)
     */
    private Post $myPost;

    /**
     * @param string $comText
     */
    public function __construct(string $comText)
    {
        $this->comText = $comText;
    }

    /**
     * @return int
     */
    public function getComId(): int
    {
        return $this->comId;
    }

    /**
     * @param int $comId
     */
    public function setComId(int $comId): void
    {
        $this->comId = $comId;
    }




    /**
     * @return string
     */
    public function getComText(): string
    {
        return $this->comText;
    }

    /**
     * @param string $comText
     */
    public function setComText(string $comText) : void
    {
        $this->comText = $comText;
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
    public function setCreator(User $creator) : void
    {
        $this->creator = $creator;
    }

    /**
     * @return Post
     */
    public function getMyPost() : Post
    {
        return $this->myPost;
    }

    /**
     * @param Post $myPost
     */
    public function setMyPost(Post $myPost): void
    {
        $this->myPost = $myPost;
    }
}