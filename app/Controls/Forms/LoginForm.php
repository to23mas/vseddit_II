<?php declare(strict_types=1);

namespace App\Controls\Forms;

use Nette;
use Nette\Application\UI\Form;

class LoginForm extends Form
{
    static function createForm(): Form
    {
        $form = new Form;

        $form   ->addText('name', 'Name: ')
                ->setRequired('You dont have a NAME ?');

        $form   ->addPassword('passw','Password: ')
                ->setRequired('It wont work without password!');

        $form   ->addSubmit('send', 'Login');
        return $form;
    }
}