<?php


/**
 * Skeleton subclass for representing a row from the 'tokens' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Wed Jul 23 14:20:09 2014
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class token extends Basetoken {
    
    public static function createTokenForUser($user){
        $token = new Tokens();
        
    }

    public static function check(sfGuardUser $user, $token){
        
        $c=new Criteria();
        $c->add(tokenPeer::USER_ID, $user->getId());
        $c->add(tokenPeer::TOKEN,$token);
        //$c->add(tokenPeer::ACTIVE, 1);
        
        $t = tokenPeer::doSelect($c);
        
        return $t != null;
    }
    
} // token
