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

    public function preExecute() {
        if (sfConfig::get('sf_environment') == 'prod')
            $this->getResponse()->setContentType('application/json');
    }

    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'index');
    }
    
    public function executeError403(sfWebRequest $request){
        $retval = array('error' => 403, 'description'=>'Not authenticated, please use login function to continue.');
        return $this->renderText(json_encode($retval));
    }
    
    public function executeCheckToken(sfWebRequest $request){
        $username = $request->getParameter('username');
        $token = $request->getParameter('token');
        
        //die($username. " ".$token);

        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($request->getParameter('app_token'))) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = sfGuardUserPeer::retrieveByUsername($username);

        //The user has wrong token
        if (!Token::check($user, $token)) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }
        $retval = array('success' => true);
        return $this->renderText(json_encode($retval));
    }

    /**
     * Validates a user and logges it in.
     * @param username
     * @param password
     * See routes for url, params via get
     */
    public function executeLogInUser(sfWebRequest $request) {
        $username = $request->getParameter('username');
        $password = $request->getParameter('password');

        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($request->getParameter('app_token'))) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = sfGuardUserPeer::retrieveByUsername($username);

        //The user has wrong password
        if (!$user->checkPassword($password)) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        //We authenticate the user, so we have session, or authentified variable.
        $this->getUser()->signin($user, $remenmber = false);
        
        //Everything OK!, we validate and log the user in
        $token = TokenPeer::retrieveToken($user);
        $retval = array('success' => true, 'user' => array('username' => $username, 'token' => $token->getToken()));
        return $this->renderText(json_encode($retval));
    }
    
    /**
     * Validates a user and logges it in.
     * @param username
     * @param password
     * See routes for url, params via get
     */
    public function executeLogOutUser(sfWebRequest $request) {
        $username = $request->getParameter('username');
        $token = $request->getParameter('token');

        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($request->getParameter('app_token'))) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = sfGuardUserPeer::retrieveByUsername($username);

        //The user has wrong token
        if (!Token::check($user, $token)) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        //We authenticate the user, so we have session, or authentified variable.
        $this->getUser()->signout();

        $retval = array('success' => true);
        return $this->renderText(json_encode($retval));
    }

    /**
     * Validates a user and logges it in.
     * @param username
     * @param password
     * See routes for url, params via get
     */
    public function executeGetInfo(sfWebRequest $request) {
        $who_id = $request->getParameter('who');
        $username = $request->getParameter('username');
        $token = $request->getParameter('token');

        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($request->getParameter('app_token'))) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = sfGuardUserPeer::retrieveByUsername($username);

        //The user has wrong token
        if (!Token::check($user, $token)) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        $who = sfGuardUserPeer::retrieveByUsername($who_id);
        
        //The user asked does not exists
        if ($who == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }
        
        //OK!, we return user info.
        $retval = array('success' => true, 'user' => $who->getProfile()->expose());
        return $this->renderText(json_encode($retval));
    }

    /**
     * Creates a user.
     * See routes for url, params via POST
     */
    public function executeCreateUser(sfWebRequest $request) {        
        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($request->getParameter('app_token'))) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }
        $username = $request->getParameter('username');
        $password = $request->getParameter('password');

        $nombre = $request->getParameter('nombre');
        $apellidos = $request->getParameter('apellidos');
        $email = $request->getParameter('email');

        //The user already exists?
        if(sfGuardUserPeer::retrieveByUsername($username)!= null){
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        $user = new sfGuardUser();
        $user->setPassword($password);
        $user->setUsername($username);
        $user->save();

        $profile = $user->getProfile();
        $profile->setFirstname($nombre);
        $profile->setLastname($apellidos);
        $profile->setEmail($email);
        $profile->save();

        $token = Token::createTokenForUser($user);

        $retval = array('success' => true, 'user' => array('username' => $username, 'token' => $token->getToken()));
        return $this->renderText(json_encode($retval));
    }

}
