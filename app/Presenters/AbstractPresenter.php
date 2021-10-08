<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

abstract class AbstractPresenter extends Presenter
{

    protected function startup(): void
    {
        parent::startup();


    }

    protected function beforeRender(): void {
        parent::beforeRender();
        $this->template->parentLayout = __DIR__.'/templates/@layout.latte';
    }

}