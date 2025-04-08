<?php

declare(strict_types=1);

namespace App\Presentation\Sign;

use Form\RegisterForm;
use Form\SignInForm;
use Nette;


final class SignPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private SignInForm $signInForm,
        private RegisterForm $registerForm,)
    {
        parent::__construct();
        $this->addComponent($signInForm, "signInForm");
        $this->addComponent($registerForm, "registerForm");
    }
    
   /**
	 * odhlášení
	 *
	 * @return void
	 */
	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Odhlášení proběhlo úspěšně.');
		$this->redirect('Sign:in');
		//$this->redirect('in');
	}
}
