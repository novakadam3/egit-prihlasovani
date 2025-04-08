<?php 

namespace Form;

use AdminModule\FormFactory\UserFormFactory;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use UsersModel;

class EditUserForm extends Control{

    public function __construct(
        private UsersModel $usersmodel,
        private UserFormFactory $userFormFactory){
    }
    public function render(){
        $this->template->setFile(__DIR__ . '/template.latte');
        $this->template->render();
    }

    public function fillData($user){
        if($user){
            $this['form']->setDefaults([
                "id" => $user->id,
                "name" => $user->name,
                "surname" => $user->surname,
                "username" => $user->username,
                "email" => $user->email,
                "phone" => $user->phone,
                "role" => $user->role,
                "is_active" => $user->is_active,
            ]);
        }
    }

    public function createComponentForm():Form {
        $form = $this->userFormFactory->createComponentForm();
        $form->addHidden("id", "ID uživatele");
        $password = $form->addPassword("password", "Heslo");

        $form->addPassword("password2", "Heslo znovu")
            ->addConditionOn($password, Form::Filled)
                ->setRequired('Prosím potvrďte heslo')
                ->addRule(Form::Equal, 'Hesla se neshodují', $form['password']);

        $form->addSelect("role", "Role", [
            "admin" => "Admin",
            "user" => "Uživatel",
        ])
        ->setPrompt("Vyberte roli")
        ->setRequired("Vyberte roli uživatele.");

        $form->addCheckbox("is_active", "Aktivní");


        $form->addSubmit("submit", "Uložit");

        $form->onSuccess[] = [$this, "process"];

        return $form;
    }

    public function process($form, $values):void {
        $this->usersmodel->update([
            "name" => $values->name,
            "surname" => $values->surname,
            "username" => $values->username,
            "email" => $values->email,
            "phone" => $values->phone,
            "password" => password_hash($values->password, PASSWORD_DEFAULT),
            "role" => $values->role,
            "is_active" => $values->is_active,
        ], $values->id);
        
        $this->getPresenter()->flashMessage("Uživatel byl úspěšně upraven.", "success");
    }
}