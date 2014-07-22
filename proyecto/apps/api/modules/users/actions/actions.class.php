<?php

/**
 * users actions.
 *
 * @package    PhpProject1
 * @subpackage users
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class usersActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'index');
    }

    /**
     * Testing only: Returns an array of users with their name.
     */
    public function executeGetUsers(sfWebRequest $request) {
        $users = sfGuardUserPeer::doSelect(new Criteria());
        foreach($users as $user) $retU[] = $user->getUsername();
        $u['usuarios'] = $retU;
        return $this->renderText(json_encode($u));
    }
    
    /**
     * Validates a user and logges it in.
     * @param username
     * @param password
     * See routes for url, params via get
     */
    public function executeValidateUser(sfWebRequest $request) {
        $username = $request->getParameter('username');
        $password = $request->getParameter('password');
        
        $user = sfGuardUserPeer::retrieveByUsername($username);
        if($user->checkPassword($password)){
            $retval = array('success'=>true,'user'=>array('username'=>$username, 'token'=>$user->getSalt()));
            return $this->renderText(json_encode($retval));
        }else{
            $retval = array('success'=>false);
            return $this->renderText(json_encode($retval));
        }
    }

}
