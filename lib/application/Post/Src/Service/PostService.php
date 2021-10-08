<?php declare(strict_types=1);
namespace Tom\Application\Post\Service;

use Nette;

use Tom\Application\Comment\Entity\Data\Comment;
use Tom\Application\Post\Entity\Data\Post;
use Tom\Application\User\Entity\Data\User;

use Doctrine\ORM\EntityRepository;

use Nettrine\ORM\EntityManagerDecorator;


class PostService
{
    protected EntityRepository $repository;
    protected EntityManagerDecorator $entityManager;

    public function __construct(EntityManagerDecorator $entityManagerDecorator)
    {
        $this->entityManager = $entityManagerDecorator;
        $this->repository = $entityManagerDecorator->getRepository(Post::class);
    }

    /**
     * Vrátí jeden post podle zadanýho ID
     *
     * @param int $id
     * @return Post
     */
    public function getOne(int $id) : Post
    {
        return $this->repository->findOneBy(['postId' =>  $id]);
    }

    /**
     * smaže zadaný komentář
     * @param Comment $comment
     */
    public function removeOneComment(Comment $comment) :void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    /**
     * smaže jeden post
     * @param Post $post
     */
    public function removeOnePost(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * vrátí Collection komentářů příslušící jednomu Postu
     * @param int $id id Postu
     * @return object
     */
    public function getMyComments(int $id) : object
    {
        $post = $this->repository->findOneBy(['postId' =>  $id]);
        return $post->getMyComments();
    }

    /**
     * Comment
     * Vytvoří nový, přiřadí mu uživatele, který jej přidal, přiřazení K Postu
     *
     * Post
     * komentář se přidá do kolekce comentářů
     *
     * User
     * komentář s vloží do KOlekce komentářů které uživatel napsal
     *
     * @param string $text text komentáře
     * @param Post $post
     * @param User $user
     */
    public function saveToDBComment(string $text, Post $post, User $user) : void
    {
        $comment = new Comment($text);

        $user->addComment($comment);
        $post->addComment($comment);

        $comment->setMyPost($post);
        $comment->setCreator($user);

        $this->entityManager->persist($post);

        $this->entityManager->flush();
    }

}