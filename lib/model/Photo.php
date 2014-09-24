<?php


/**
 * Skeleton subclass for representing a row from the 'photos' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Mon Sep 22 16:45:29 2014
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Photo extends BasePhoto {
    
    public function __construct(){
        parent::__construct();
        $this->setUploadedAt(time());
    }

    public static function exposePhotoList($list) {
        # $list<$user> typeof photo
        $retval = array();
        foreach ($list as $user) {
            $retval[] = $user->expose();
        }
        return $retval;
    }
    
    private function getUser(){
        return sfGuardUserPeer::retrieveByPK($this->getUserId());
    }
    
    public function expose() {
        //return get_object_vars($this);
        return array('username'=>$this->getUser()->getUsername(), 'eventname' => $this->getEvent()->getName(),'uploaded_at'=>$this->getUploadedAt(), 'photo_id'=>$this->getId());
    }
    
} // Photo