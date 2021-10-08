<?php declare(strict_types=1);

namespace App\Modules\ForumModule;

use App\Presenters\AbstractAuthorizedPresenter;
use Nette;
use Nette\Application\UI\Form;

use App\Controls\Forms\SubForumForm;
use App\Controls\Forms\PostForm;

use Tom\Application\SubForum\Service\SubForumService;
use Tom\Application\SubForum\Entity\Data\SubForum;
use Tom\Application\User\Service\UserService;

final class SubForumPresenter extends AbstractAuthorizedPresenter
{

    /** @var int|null @persistent */
    public ?int $subId = null;

    private $isSubscribed = false;

    /** @var SubForumService @inject */
    public SubForumService $subForumService;
    /** @var UserService @inject */
    public UserService $userService;



    /**
     * Render všech SubFor
     * @throws Nette\Application\AbortException
     */
    public function renderAllSubForums(string $title="All SUB-Forums"): void
    {
        $this->template->title = $title;

        if($title === "Created Forums"){
            $this->template->subForums = $this->userService->getMyCreatedForums($this->getUserId());
        }elseif($title === "Subscribed Forums"){
            $this->template->subForums = $this->userService->getMySubscribedForums($this->getUserId());
        }else{
            $this->template->subForums = $this->subForumService->getAll();
        }

    }

    /**
     * render jednoho SubFora
     * @param int $id
     * @throws Nette\Application\AbortException
     */
    public function renderSubForum(?int $id = null) : void
    {

        if(!$id){
            $this->redirect('SubForum:allSubForums');
        }

        $this->template->isSubscribed = $this
            ->userService
            ->isSubscribed($this
                ->userService
                ->getOneUser($this
                    ->getUserId()),
                    $this->subForumService->getOne($id));


        $this->subId = $id;
        $this->template->subId = $this->subId;
        $this->template->userId = $this->getUserId();


        $this->template->subForum = $this->subForumService->getone($id);
        $this->template->posts = $this->subForumService->getMyPosts($id);
    }

    /**
     * @param string $form
     * @param int $subId
     * @throws Nette\Application\AbortException
     */
    public function handleForm(string $form = '0', int $subId=0) : void
    {
        $this->template->forms = $form;
        $this->subId=$subId;
        if($this->isAjax()) {
            if ($form) {
                $this->redrawControl();
            } else {
                $this->redirect('SubForum:allSubForums');
            }
        }
    }


    /**
     * maže nebo přidáva suby k uživateli
     * @param int $subId
     * @param bool $bool
     * @throws Nette\Application\AbortException
     */
    public function actionSubscribe(int $subId=0, bool $bool=true) : void
    {
        $user = $this->userService->getOneUser($this->getUserId());
        $subv = $this->subForumService->getOne($subId);

        if($bool){
            $this->userService->setSubscriptions($user, $subv);
        }else{
            $this->userService->unsetSubscriptions($user, $subv);
        }
        $this->redirect('SubForum:subForum', $subId);
    }

    public function actionDeleteSub(int $subId=0) : void
    {
        $subforum = $this->subForumService->getOne($subId);
        $this->subForumService->deleteOne($subforum);
        $this->redirect('SubForum:subForum');
    }

    /**
     * vytváří formulář na Posty
     * @return Form
     */
    public function createComponentPostForm() : Form
    {
        $postForm = PostForm::createForm();


        $postForm   ->addSubmit('create', 'Create')
                    ->setHtmlAttribute('class', 'mt-1 ml-2 float-right btn btn-dark');
        // ID SubFora

        $postForm   ->onSuccess[] = [$this, 'createPost'];

        return $postForm;
    }

    /**
     * validuje formulář s Posty
     * @param Nette\Forms\Controls\Button $button
     * @param $data
     * @throws Nette\Application\AbortException
     */
    public function createPost(Form $form, $data) : void
    {
        $userId = $this->getUserId();

        $this->userService
            ->saveToDBPost($data->title,
                $data->text,
                $userId,
                $this->subForumService->getOne($this->subId));


        $form->reset();
        $this->redirect('SubForum:SubForum', $this->subId);
    }


    /**
     * vrátí ID právě přihlášeného usera
     *
     * @return ?int
     */
    private function getUserId() : ?int
    {
        return $this->getUser()->getIdentity()->getId();
    }
}
