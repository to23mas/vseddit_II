<?php declare(strict_types=1);

namespace App\Controls\Forms;

use Nette;
use Nette\Application\UI\Form;

class SubForumForm
{
    static function createForm() : Form
    {
        $form = new Form;

        $form   ->addText('title', 'Title:')
                ->setRequired('Zadejte prosím jméno');

        $form   ->addText('description', 'Description:')
                ->setRequired('Set some description');

        return $form;
    }
}