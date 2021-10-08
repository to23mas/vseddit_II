<?php declare(strict_types=1);

namespace App\Modules\ForumModule;

use App\Presenters\AbstractAuthorizedPresenter;
use Nette;

use App\Controls\Forms\PostForm;
use App\Controls\Forms\SubForumForm;

use Tom\Application\SubForum\Entity\Data\SubForum;
use Tom\Application\User\Service\UserService;
use Tom\Application\SubForum\Service\SubForumService;

use Nette\Application\UI\Form;

final class FormsPresenter extends AbstractAuthorizedPresenter
{

    /** @var SubForumService @inject */
    public SubForumService $subVsedditService;

    /** @var UserService @inject */
    public UserService $userService;

    public function renderForms(string $formName):void
    {
        if ($formName) {
            $this->template->forms = $formName;

        } else {
            $this->flashMessage('Nevim o co se snažíš, tento formulář neexistuje');

        }

    }

    /**
     * create component Formulář = SubForum
     * @return Form
     */
    public function createComponentSubForumForm() : Form
    {
        $subForm = SubForumForm::createForm();
        $subForm->addSubmit('create', 'Create')
            ->setHtmlAttribute('class', 'mt-1 ml-2 float-right btn btn-dark');

        $subForm->onSuccess[] = [$this, 'createSub'];

        return $subForm;
    }

    /**
     * Pokud je formulář se SubVs... odeslán
     * data se uloží do database
     * @param Form $form
     * @param $data
     * @throws Nette\Application\AbortException
     */
    public function createSub(Form $form, $data) : void
    {
        //TODO redirect na stránku s vytvořeným Subem
        if($this->subVsedditService->validateTitle($data->title)){
            $userId = $this->getUser()->getIdentity()->getId();

            $this->userService->saveToDBSubForum($data->title, $data->description, $userId);
            $this->flashMessage('Sub was successfully created');
            $this->redirect('SubForum:allSubForums');
        }
        $this->flashMessage('Title already exists');
        $this->redirect('SubForum:allSubForums');
    }

}