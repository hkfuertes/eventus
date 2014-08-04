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

    /**
     * Validates a user and logges it in.
     * @param username
     * @param password
     * See routes for url, params via get
     */
    public function executeValidateUser(sfWebRequest $request) {
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
    public function executeGetInfo(sfWebRequest $request) {
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

        //OK!, we return user info.
        $retval = array('success' => true, 'user' => $user->getProfile()->expose());
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

        //OK!, we generate the user.
        $username = $request->getParameter('username');
        $password = $request->getParameter('password');

        $nombre = $request->getParameter('nombre');
        $apellidos = $request->getParameter('apellidos');
        $email = $request->getParameter('email');


        $user = new sfGuardUser();
        $user->setPassword($password);
        $user->setUsername($username);
        $user->save();

        $profile = $user->getProfile();
        $profile->setNombre($nombre);
        $profile->setApellidos($apellidos);
        $profile->setEmail($email);
        $profile->save();

        $token = Tokens::createTokenForUser($user);

        $retval = array('success' => true, 'user' => array('username' => $username, 'token' => $token->getToken()));
        return $this->renderText(json_encode($retval));
    }

}
