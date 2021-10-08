<?php
declare(strict_types=1);
/**
 * @author Tomáš Míčka
 */

namespace Tom\Table;


use Nette\Application\UI\Form;


class Forms extends Form {

    static function createEditForm($columns): Form {
        $form = new Form;

        foreach ($columns as $column) {
            $title = $column->getName();

            if ($title === 'ID') {
                $form->addText(str_replace(' ', '_', $title), $title . ': ')
                    ->setEmptyValue('1')
                    ->setDisabled();
            }elseif($title === 'Del/edit') {
                continue;

            }else {
                $form->addText(str_replace(' ', '_', $title), $title . ': ');
            }
        }


        $form->addSubmit('edit', 'edit')
            ->setHtmlAttribute('class','mr-2 btn btn-secondary');

        $form->addSubmit('hide', 'hide')
            ->setHtmlAttribute('class','btn btn-secondary');

        return $form;
    }

    public static function createAddForm($columns): Form {
        $form = new Form;

        foreach ($columns as $column) {
            $title = $column->getTitle();

            if ($title === 'ID' || $title === 'Del/edit') {continue;}

            $form->addText(str_replace(' ', '_', $title), $title . ': ');

        }

        $form->addSubmit('add', 'add')
                ->setHtmlAttribute('class','btn btn-secondary');
        //        $form->addSubmit('hide', 'hide');

        return $form;
    }
}