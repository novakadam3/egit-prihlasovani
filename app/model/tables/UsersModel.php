<?php

use Nette\Database\Explorer;

/**
 * UserModel
 *
 * 
 */
class UsersModel extends Repository\MainRepository
{
    
    function __construct(private Explorer $db) 
    {
        parent::__construct( $db, "users" );
    }

    
}