<?php 

namespace Form;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use SignLogsModel;
use UsersModel;

class SignInForm extends Control{
    public function __construct(
        private UsersModel $usersModel,
        private SignLogsModel $signLogsModel,) {

    }

    public function render(){
        $this->template->setFile(__DIR__ . '/template.latte');
        $this->template->render();
    }

    public function createComponentForm():Form {
        $form = new Form();

        $form->addText("username", "Uživatelské jméno");
        $form->addPassword("password", "Heslo");

        $form->addCheckbox("remember", "Zapamatovat si mě");

        $form->addSubmit("submit", "Přihlásit se");

        $form->onSuccess[] = [$this, "process"];

        return $form;
    }

    public function process($form, $values):void {
        try {
            if ($values->remember) {
                $this->getPresenter()->getUser()->setExpiration('+ 14 days', FALSE);
            } else {
                $this->getPresenter()->getUser()->setExpiration('+ 1 days', FALSE);
            }
            $this->getPresenter()->getUser()->login($values->username, $values->password);
            $httpRequest = $this->getPresenter()->getHttpRequest();
            $ip = $httpRequest->getRemoteAddress();
            $this->signLogsModel->insertLog($ip);
            $this->getPresenter()->flashMessage("Přihlášení proběhlo úspěšně.", "success");
            $this->getPresenter()->redirect("Home:default");
		} catch (\Nette\Security\AuthenticationException $e) {
			$this->getPresenter()->flashMessage($e->getMessage(), "danger");
			return;
		}
    }
}