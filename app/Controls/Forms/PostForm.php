<?php declare(strict_types=1);

namespace App\Controls\Forms;

use Nette;
use Nette\Application\UI\Form;

class PostForm
{
    static function createForm() : Form
    {
        $form = new Form;

        $form   ->addText('title', 'Title:')
                ->setRequired('Zadejte prosím title');

        $form   ->addTextArea('text', 'Text:')
                ->setRequired('Set some description');

        return $form;
    }

}