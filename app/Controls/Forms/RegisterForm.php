<?php declare(strict_types=1);

namespace App\Controls\Forms;

use Nette;
use Nette\Application\UI\Form;

class RegisterForm
{
    static function createForm() : Form
    {
        $form = new Form;

        $form   ->addText('name', 'Nickname:')
                ->setRequired('Zadejte prosím jméno');

        $form   ->addPassword('password', 'Password:')
                ->setRequired('You dont want a password??');

        $form   ->addPassword('passwordSecond', 'Password again:')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule($form::EQUAL, 'Hesla se neshodují', $form['password'])
                ->setOmitted();

        $form   ->addSubmit('send', 'Register');

        return $form;
    }
}