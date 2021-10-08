<?php declare(strict_types=1);
namespace Tom\Application\User\Service;


use Nette;
use Nette\Security\SimpleIdentity;
use Nette\Security\Passwords;

use Tom\Application\User\Entity\Data\User;

use Doctrine\ORM\EntityRepository;

use Nettrine\ORM\EntityManagerDecorator;

class Authenticator implements  Nette\Security\Authenticator
{
    protected EntityManagerDecorator $entityManager;
    protected EntityRepository $repository;

    /** @var Passwords */
    private Passwords $passwords;

    public function __construct(EntityManagerDecorator $entityManagerDecorator,
                                Nette\Security\Passwords $passwords)
    {
        $this->passwords = $passwords;
        $this->entityManager = $entityManagerDecorator;
        $this->repository = $entityManagerDecorator->getRepository(User::class);
    }


    public function authenticate(string $userName, string $password) : SimpleIdentity
    {
        $user = $this->repository->findOneBy(['userNick' => $userName]);


        if (!$user) {
            exit;
        }

        if (!$this->passwords->verify($password, $user->getUserPassword())) {
            exit;
        }

        return new SimpleIdentity($user->getUserId());

    }


}