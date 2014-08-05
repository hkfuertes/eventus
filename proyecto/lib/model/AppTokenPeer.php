<?php


/**
 * Skeleton subclass for performing query and update operations on the 'app_tokens' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Mon Aug  4 15:38:27 2014
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class AppTokenPeer extends BaseAppTokenPeer {

    public static function checkExistAndActive($token){
        $c = new Criteria();
        $c->add(self::TOKEN,$token);
        $c->add(self::ACTIVE, 1);
        
        return self::doCount($c) > 0;
    }
    
} // AppTokenPeer