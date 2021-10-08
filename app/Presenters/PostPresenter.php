<?php declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

use Tom\Application\Comment\Service\CommentService;
use Tom\Application\Post\Service\PostService;
use Tom\Application\User\Service\UserService;

final class PostPresenter extends Nette\Application\UI\Presenter
{

    /** @var int */
    private $postId;

    /** @var PostService @inject */
    public PostService $postService;

    /** @var CommentService @inject */
    public CommentService $commentService;

    /** @var UserService @inject */
    public UserService $userService;



    public function renderPost(int $id=0) : void
    {
        if(!$id){
            $this->flashMessage("takový post neexistuje");
            $this->redirect('SubForum:allSubForums');
        }

        $this->postId = $id;

        $this->template->postId = $this->postId;
        $this->template->userId = $this->getUserId();
        $this->template->post = $this->postService->getOne($id);

        $this->template->comments = $this->postService->getMyComments($id);
    }

    public function handleComment(bool $i=false) : void
    {
        if($i){
            if($this->isAjax()){
                $this->template->form = true;
                $this->redrawControl('comment');
            }
        }else{
            $this->redrawControl('comment');

        }
    }

    public function createComponentCommentForm() : Form
    {
        $commentForm = new Form;

        $commentForm->addText('comment','Text: ')
                    ->setRequired('HM??');

        $commentForm->addHidden('user', $this->getUserId());

        $commentForm->addHidden('post', $this->postId);

        $commentForm->addSubmit('add', 'Add')
                    ->setHtmlAttribute('class', 'mt-1 ml-2 float-right btn btn-dark');

        $commentForm->onSuccess[] = [$this, 'addComment'];

        return $commentForm;
    }

    public function addComment(Form $form, $data) : void
    {
       $this->postService
             ->saveToDBComment($data->comment,
                               $this->postService->getOne((int)$data->post),
                               $this->userService->getOneUser((int)$data->user));

        $this->redirect('Post:post', (int)$data->post);
    }

    public function actionDelete(int $comId=0, int $postId=0) : void
    {
        $comment = $this->commentService->getOneComment($comId);

        $this->postService->removeOneComment($comment);
        $this->redirect('Post:post', $postId);
    }

    public function actionDeletePost(int $postId=0, int $subId=0):void
    {
        $post = $this->postService->getOne($postId);

        $this->postService->removeOnePost($post);
        $this->redirect('SubForum:subForum', $subId);
    }


    // TODO tohle používám ve více presenterech, asi by šlo dát dohromady
    private function getUserId() : int
    {
        return $this->getUser()->getIdentity()->getId();
    }
}