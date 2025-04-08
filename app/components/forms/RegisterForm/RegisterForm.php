<?php 

namespace Form;

use AdminModule\FormFactory\UserFormFactory;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use UsersModel;

class RegisterForm extends Control{

    public function __construct(
        private UsersModel $usersmodel,
        private UserFormFactory $userFormFactory){
    }
    public function render(){
        $this->template->setFile(__DIR__ . '/template.latte');
        $this->template->render();
    }

    public function createComponentForm():Form {
        $form = $this->userFormFactory->createComponentForm();

        $form->addPassword("password", "Heslo")
            ->setRequired("Zadejte heslo.");

        $form->addPassword("password2", "Heslo znovu")
            ->setRequired("Zadejte heslo znovu.")
                ->addRule(Form::Equal, "Hesla se neshodují.", $form["password"]);

        $form->addSubmit("submit", "Zaregistrovat se");

        $form->onSuccess[] = [$this, "process"];

        return $form;
    }

    public function process($form, $values):void {
        try{
            $insertedUser = $this->usersmodel->insert([
                "name" => $values->name,
                "surname" => $values->surname,
                "username" => $values->username,
                "email" => $values->email,
                "phone" => $values->phone,
                "password" => password_hash($values->password, PASSWORD_DEFAULT),
                "role" => "user",
                "is_active" => 1,
            ]);
            $this->getPresenter()->getUser()->login($values->username, $values->password);
            $this->getPresenter()->redirect("Home:");
        }catch(UniqueConstraintViolationException $e){
            $form->addError("Uživatelské jméno nebo e-mail již existuje.");
            return;
        }
    }
}