<?php

use Nette\Database\Explorer;

/**
 * SignLogsModel
 *
 * 
 */
class SignLogsModel extends Repository\MainRepository
{
    
    function __construct(private Explorer $db) 
    {
        parent::__construct( $db, "sign_logs" );
    }

    /**
     * Inserts log of ip and date created when user signs
     *
     * @param string $ip
     * @return void
     */
    public function insertLog($ip){
        $this->insert([
            'date_sign' => new \Nette\Utils\DateTime(),
            'ip_address' => $ip
        ]);
    }

    
}