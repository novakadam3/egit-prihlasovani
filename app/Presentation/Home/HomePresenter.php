<?php

declare(strict_types=1);

namespace App\Presentation\Home;

use Component\Grid\UsersGrid;
use Form\EditUserForm;
use Nette;
use UsersModel;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private UsersGrid $usersGrid,
        private EditUserForm $editUserForm,
        private UsersModel $usersModel,
    )
    {
        parent::__construct();
        $this->addComponent($this->editUserForm, "editUserForm");
    }
    public function startup(): void
    {
        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect("Sign:in");
        }
    }

    public function renderEditUser($userId){
        $user = $this->usersModel->getById($userId);
        if(is_null($user)){
            $this->flashMessage("Uživatel neexistuje.", "danger");
            $this->redirect("default");
        }
        if($this->getUser()->getId() != $userId && !$this->getUser()->isAllowed("editUser")){
            $this->flashMessage("Nemáte oprávnění editovat tohoto uživatele.", "danger");
            $this->redirect("Home:default");
        }
        $this->editUserForm->fillData($user);
    }

    public function renderDefault(): void
    {
        $httpRequest = $this->getHttpRequest();
    }

    public function createComponentUsersGrid(): UsersGrid
    {
        return $this->usersGrid;
    }
}
