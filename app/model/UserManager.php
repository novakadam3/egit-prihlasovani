<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use Nette\Security\IIdentity;
use Nette\Security\SimpleIdentity;
use UserModel;
use UsersModel;

/**
 * Users management.
 */
class UserManager implements Nette\Security\Authenticator
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'email',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_EMAIL = 'email',
		COLUMN_ROLE = 'role';


	public function __construct(private UsersModel $usersModel)
	{
	}


	/**
	 * Performs an authentication.
	 *
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Nette\Security\IIdentity
	{
		if(!$username || !$password) {
            throw new Nette\Security\AuthenticationException('Uživatelské jméno nebo heslo nejsou zadané', self::InvalidCredential);
		}
	
        $passwords = new Passwords();
        $row = $this->usersModel->getRows()->where("username = ?", $username)->fetch(); // našlo uživatele?
		if (is_null($row)) { // pokud nenašlo
			throw new Nette\Security\AuthenticationException('Uživatel neexistuje', self::IdentityNotFound);
		}
        if($row->is_active == 0) { // pokud je uživatel neaktivní
            throw new Nette\Security\AuthenticationException('Uživatel byl deaktivován', self::IdentityNotFound);
        }
		elseif (!$passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) { // pokud heslo neodpovídá
			throw new Nette\Security\AuthenticationException('Špatné heslo', self::InvalidCredential);
		} 

		$arr = $row->toArray();
		
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		return new Nette\Security\SimpleIdentity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}
}