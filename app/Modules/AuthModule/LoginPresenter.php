<?php declare(strict_types=1);

namespace App\Modules\AuthModule;

use App\Presenters\AbstractPresenter;
use Nette;
use Nette\Application\UI\Form;
use Tom\Application\User\Service\UserService;
use Tom\Application\User\Service\Authenticator;

use App\Controls\Forms\RegisterForm;
use App\Controls\Forms\LoginForm;

final class LoginPresenter extends AbstractPresenter
{

    private ?bool $login = true;
    private ?bool $nav = true;
    private ?bool $register = false;

    /**
     * @var UserService @inject
     */
    public UserService $userService;


    /**
     * @var Authenticator @inject
     */
    public Authenticator $authenticator;


    /**
     * render method
     * login===true => vykreslení rozcestí na login nebo register
     * lagin === false AND register === true => vykreslení register obrazovky
     * lagin === false AND register === false => vykreslení login obrazovky
     *
     */
    public function renderDefault() : void
    {
        if($this->getUser()->isLoggedIn()){
            $this->redirect(':Forum:Forum:default');
        }
        $this->template->nav = $this->nav;
        $this->template->login = $this->login;
        $this->template->register = $this->register;
    }

    /**
     * <a n:href="showLogin!" class="ajax">
     * ukáže login formulář
     */
    public function handleShowLogin() : void
    {
        if ($this->isAjax()) {
            $this->login = false;
            $this->redrawControl('loginScreen');
        }
    }

    /**
     * <a n:href="showRegister!" class="ajax">
     * ukáže register formulář
     */
    public function handleShowRegister() : void
    {
        if ($this->isAjax()) {
            $this->login = false;
            $this->register = true;
            $this->redrawControl('loginScreen');
        }
    }

    /**
     * <a n:href="back!" class="ajax">
     * zpět na rozcestí
     */
    public function handleBack() : void
    {
        if($this->isAjax()) {
            $this->login = true;
            $this->register = false;
            $this->redrawControl('loginScreen');
        }
    }

    /**
     * componenta Register formuláře
     * @return Form
     */
    public function createComponentRegisterForm() : Form
    {
        $registerForm = RegisterForm::createForm();
        $registerForm->onSuccess[] = [$this, 'registerSucceeded'];

        return $registerForm;
    }

    /**
     * validace registračního formulářa - unikátní jméno = uložení do DB
     *
     * @param Form $form
     * @param \stdClass $data
     * @throws Nette\Application\AbortException
     */
    public function registerSucceeded(Form $form, \stdClass $data) : void {

        //validace jestli je userName v DB
        if($this->userService->validateNickname($data->name)){
            $this->userService->saveToDB($data->name, $data->password);
            $this->getUser()->login($data->name, $data->password);
            $this->flashMessage('Jsi zaregistrován a přihlášen');
            $this->redirect('Forum:default');

        }else{
            $this->flashMessage('Tento nick už existuje. Try again');
            $this->login = false;
            $this->register = true;
        }
    }

    /**
     * componenta Login formuláře
     * @return Form
     */
    public function createComponentLoginForm() : Form
    {
        $loginForm = LoginForm::createForm();
        $loginForm->onSuccess[] = [$this, 'loginSucceeded'];

        return $loginForm;
    }

    /**
     * přihlášení uživatele s redirectem na Vseddit:delault v případě úpěchu
     * v případě neúspěchu flash message
     *
     * @param Form $form
     * @param \stdClass $data
     * @throws Nette\Application\AbortException
     */
    public function loginSucceeded(Form $form, \stdClass $data) : void
    {
            $this->getUser()->login($data->name, $data->passw);
            if($this->getUser()->isLoggedIn()){
                $this->redirect(':Forum:Forum:default');
            }
            $this->redirect('Login:error');

    }

}
