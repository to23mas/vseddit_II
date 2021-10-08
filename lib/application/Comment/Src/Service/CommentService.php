<?php declare(strict_types=1);

namespace Tom\Application\Comment\Service;

use Nette;

use Tom\Application\Comment\Entity\Data\Comment;

use Doctrine\ORM\EntityRepository;

use Nettrine\ORM\EntityManagerDecorator;

class CommentService
{
    protected EntityManagerDecorator $entityManager;
    protected EntityRepository $repository;

    public function __construct(EntityManagerDecorator $entityManagerDecorator)
    {
        $this->entityManager = $entityManagerDecorator;
        $this->repository = $entityManagerDecorator->getRepository(Comment::class);
    }


    /**
     * Vrací jeden komentář podle zadaného ID comentáře
     *
     * @uses testing_purpose
     *
     * @param int $comId
     * @return Comment
     */
    public function getOneComment(int $comId) : Comment
    {
        return $this->repository->findOneBy(['comId' =>  $comId]);
    }

}
