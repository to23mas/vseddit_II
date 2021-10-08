<?php declare(strict_types=1);
namespace Tom\Application\SubForum\Service;

use Nette;

use Nettrine\ORM\EntityManagerDecorator;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;

use Tom\Application\User\Entity\Data\User;
use Tom\Application\SubForum\Entity\Data\SubForum;

class SubForumService
{
    protected EntityManagerDecorator $entityManager;
    protected EntityRepository $repository;

    public function __construct(EntityManagerDecorator $entityManagerDecorator)
    {
        $this->entityManager = $entityManagerDecorator;
        $this->repository = $entityManagerDecorator->getRepository(SubForum::class);
    }


    /**
     * ověření názvu Subfore, jestli má unikátní název v DB
     * @param string $title název SubFora
     * @return bool
     */
    public function validateTitle(string $title): bool
    {
        $check = $this->repository->findOneBy(['subTitle' => $title]);

        if (empty($check)) {
            return true;
        }
        return false;
    }


    /**
     * vrací včechnu Subfora z DB
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * smaže jedno Sub Forum
     * @param SubForum $sub
     */
    public function deleteOne(SubForum $sub): void
    {
        $this->entityManager->remove($sub);
        $this->entityManager->flush();
    }

    /**
     * vrátí uživatelem vytvořené SubFora
     * @param User $user uživatel na kterého se ptám
     * @return Collection
     */
    public function getUsersForums(User $user) : Collection
    {
        return $user->getMySubForums();
    }


    /**
     * vrátí jedno Forum
     * @param int $id id klíč SubFora
     * @return SubForum
     */
    public function getOne(int $id) : SubForum
    {
        return $this->repository->findOneBy(['subId' =>  $id]);
    }


    /**
     * vrací Posty patřící pod jedno SubForum
     * @param int $id id SubFora
     * @return object
     */
    public function getMyPosts(int $id) :object{
        $sub = $this->repository->findOneBy(['subId' =>  $id]);
        return $sub->getMyPosts();
    }

}

