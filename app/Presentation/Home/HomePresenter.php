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
        if(is_null($user)){ // uživatel s daným id neexistuje
            $this->flashMessage("Uživatel neexistuje.", "danger");
            $this->redirect("default");
        }
        if($this->getUser()->getId() != $userId && !$this->getUser()->isAllowed("editUser")){
            $this->flashMessage("Nemáte oprávnění editovat tohoto uživatele.", "danger");
            $this->error("Nemáte oprávnění editovat tohoto uživatele.", 403);
        }
        $this->editUserForm->fillData($user);
    }

    public function createComponentUsersGrid(): UsersGrid
    {
        return $this->usersGrid;
    }
    public function createComponentEditUserForm()
    {
        return $this->editUserForm;
    }
}
