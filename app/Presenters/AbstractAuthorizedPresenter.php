<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

abstract class AbstractAuthorizedPresenter extends AbstractPresenter
{

    protected function startup(): void
    {
        parent::startup();

        if(!$this->getUser()->isLoggedIn()){
            $this->redirect(':Auth:Login:');
        }

    }

    public function actionLogout() : void {
        $this->getUser()->logout();
        $this->redirect(':Auth:Login:');
    }
}