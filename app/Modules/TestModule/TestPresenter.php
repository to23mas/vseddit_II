<?php declare(strict_types=1);

namespace App\Modules\TestModule;

use App\Facade\PalsFacade;
use App\Presenters\AbstractAuthorizedPresenter;
use Tom\Application\Pals\Service\PalsService;
use Tom\Hello\Hello;
use Tom\WhiteNoise\WhiteNoise;
use Tom\Mail\Mail;

use Nette;
use Nette\Application\UI\Form;

use Tom\Table\Table;
use Tom\Table\TableFactoryInterface;

final class TestPresenter extends AbstractAuthorizedPresenter {

    /** @var Hello @inject */
    public Hello $hello;

    /** @var WhiteNoise @inject */
    public WhiteNoise $whiteNoise;

    /** @var Mail @inject */
    public Mail $mail;

    /** @var TableFactoryInterface @inject */
    public TableFactoryInterface $tableFactory;

    /** @var PalsFacade @inject */
    public PalsFacade $palsFacade;

    public function actionDefault(): void {
        $this['table']->setData($this->palsFacade->getAll());

    }

    public function createComponentTable(): Table {
        $table = $this->tableFactory->create();

        $table->setPrimaryColumn('palId');

        $table->onCreate = function (array $values) : void {
            $this->palsFacade->create($values);
        };

        $table->onUpdate = function (array $values) : void {
            $this->palsFacade->update($values);
        };

        $table->onLoadEditData = function (int $id) : array {
            return $this->palsFacade->getSingleResultAsArray($id) ?: [];
        };

        $table->onDelete = function (int $id) : void {
            $this->palsFacade->delete($id);
        };

        $table->onEditForm = static function (string $name) : Form {
            $form = new Form();

            if ($name === 'Edit'){
                $form->addText('palId', 'Id');

            }else {
                $form->addText('palId', 'Id');
            }
            $form->addText('firstName', 'First Name');
            $form->addText('lastName', 'Last Name');



            $form->addSubmit('send', $name);
            if ($name === 'Edit') {
                $form->addSubmit('hide', 'Hide');
            }
            return $form;
        };

       // $table->addColumn('button', 'Del/edit');
        $table->addColumn('palId', 'ID');
        $table->addColumn('firstName', 'First Name');
        $table->addColumn('lastName', 'Last Name');


        return $table;
    }




    //    public function handlePage(int $page=1): void {
    //        $this->paginator->setPage($page);
    //
    //        $this['table']->getDataPerPage($this->paginator->getLength(),
    //                                       $this->paginator->getOffset());
    //
    //
    //    }
    //
    //
    //    public function renderNoise(): void
    //    {
    //        $this->whiteNoise->generateImage();
    //    }
    //
    //    public function renderMyMail(): void
    //    {
    //
    //    }
    //    public function createComponentMyMail(): Form
    //    {
    //        $form = new Form;
    //        $form->addText('message', 'message');
    //
    //        $form->addSubmit('send', 'send');
    //        $form->onSuccess[] = [$this, 'formSucceeded'];
    //        return $form;
    //    }
    //
    //    public function formSucceeded(Form $form, $data): void {
    //        $this->mail->setupMail($data->message, 'tomas.micka@ticketstream.cz');
    //        $this->mail->sendMail();
    //        $this->redirect('Test:myMail');
    //    }
}
