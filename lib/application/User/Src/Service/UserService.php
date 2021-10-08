<?php declare(strict_types=1);

namespace Tom\Application\User\Service;

use Nette;
use Nette\Security\Passwords;

use Nettrine\ORM\EntityManagerDecorator;

use Doctrine\ORM\EntityRepository;
use Tom\Application\Post\Entity\Data\Post;
use Tom\Application\SubForum\Entity\Data\SubForum;
use Tom\Application\User\Entity\Data\User;


class UserService
{
    protected EntityManagerDecorator $entityManager;
    protected EntityRepository $repository;

    public function __construct(EntityManagerDecorator $entityManagerDecorator)
    {
        $this->entityManager = $entityManagerDecorator;
        $this->repository = $entityManagerDecorator->getRepository(User::class);
    }

    /**
     * vrací jednoho uživatele podle jeho ID
     * @param int $id
     * @return User
     */
    public function getOneUser(int $id) : User
    {
        return $this->repository->findOneBy(['userId' =>  $id]);
    }

    /**
     * vrací Collection vytořených Subfor podle ID uživatele, který je vytvořil
     * @param int $id
     * @return object
     */
    public function getMyCreatedForums(int $id) : object
    {
        $user = $this->repository->findOneBy(['userId' =>  $id]);
        return $user->getMySubForums();
    }

    /**
     * vrací Collection Subfor který uživatel Subscribnul
     *
     * @param int $id ID uživatela na kterého se ptám
     * @return object
     */
    public function getMySubscribedForums(int $id) : object
    {
        $user = $this->repository->findOneBy(['userId' =>  $id]);
        return $user->getSubscriptions();
    }

    /**
     * vrací true pokud zadaný uživatel subscribnul zadaný SubForum
     * @param User $user
     * @param SubForum $subForum
     * @return bool
     */
    public function isSubscribed(User $user, SubForum $subForum) : bool
    {
        $mySubscriptions = $user->getSubscriptions();

        foreach ($mySubscriptions as $item){
            if($item === $subForum){
                return false;
            }
        }
        return true;
    }

    /**
     * set subscription between User-SubForum
     * @param User $user
     * @param SubForum $subForum
     */
    public function setSubscriptions(User $user, SubForum $subForum) : void
    {
        $user->addSubscription($subForum);
        $subForum->addSubscriber($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * ruší subscription between User and Subforum
     * @param User $user
     * @param SubForum $subForum
     */
    public function unsetSubscriptions(User $user, SubForum $subForum) : void
    {
        $user->removeSubscription($subForum);
        $subForum->removeSubscriber($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Vytváří nevého usera
     * @param string $nick
     * @param string $passw
     */
    public function saveToDB(string $nick, string $passw) : void
    {
        $hasher = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);

        $hashedPass = $hasher->hash($passw);

        $entity = new User($nick, $hashedPass);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * Validace uživatele
     *
     * při registraci se ověřuje, jestli je užavatelovo jméno již v DB
     *
     * @param string $userNick ověřované jméno
     * @return bool true => name není v DB
     */
    public function validateNickname(string $userNick) : bool {
        $entity = $this->repository->findBy(['userNick' => $userNick]);

        if(empty($entity)){
            return true;
        }
        return false;
    }

    /**
     * Ukládá nově vytvořené forum do database
     *
     * @param string $title
     * @param string $description
     * @param int $userId
     */
    public function saveToDBSubForum(string $title, string $description, int $userId) : void
    {
        $user = $this->repository->findOneBy(['userId' => $userId]);

        $sub = new SubForum($title, $description);

        $sub->setCreator($user);
        $user->addSub($sub);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Vytváří a ukládá Nový post do DB
     *
     * Userovi, který post vyvořil, jej vložído Collece= vytvořené posty
     *
     * Postu se nastavý Creator jako user, který post vytvořil
     *
     * @param string $title
     * @param string $text
     * @param int $userId
     * @param SubForum $sub
     */
    public function saveToDBPost(string $title, string $text, int $userId, SubForum $sub) : void
    {
        //najít usera
        $user = $this->repository->findOneBy(['userId' => $userId]);

        //vytvoření Postu
        $post = new Post($title, $text);
        $user->addPost($post);
        $sub->addPost($post);

        //přidání usera a SubForumu
        $post->setCreator($user);
        $post->setSubForum($sub);


        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}

