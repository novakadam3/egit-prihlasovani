<?php 

namespace AdminModule\FormFactory;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class UserFormFactory extends Control{
    public function createComponentForm():Form{
        $form = new Form();

        $form->getElementPrototype()->novalidate('novalidate'); // vypnutí http validace
        $form->addText("name", "Jméno")
            ->setRequired("Zadejte jméno.");

        $form->addText("surname", "Příjmení")
            ->setRequired("Zadejte příjmení.");

        $form->addText("username", "Uživatelské jméno")
            ->setRequired("Zadejte uživatelské jméno.");

        $form->addText("email", "E-mail")
            ->setRequired("Zadejte e-mail.")
            ->addRule(Form::Email, "Zadejte platný e-mail.");

        $form->addText("phone", "Telefonní číslo")
            ->addRule(Form::Pattern, "Zadejte platné telefonní číslo ve formátu +420123456789.", '(\+\d{1,3})?\d{9}')
            ->setRequired("Zadejte telefonní číslo.");

        return $form;
    }
}