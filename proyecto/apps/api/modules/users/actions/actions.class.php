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
     * Testing only: Returns an array of users with their name.
     */
    public function executeGetUsers(sfWebRequest $request) {
        $users = sfGuardUserPeer::doSelect(new Criteria());
        foreach ($users as $user)
            $retU[] = $user->getUsername();
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
        if ($user->checkPassword($password)) {
            $token = $user->getTokenss();
            $retval = array('success' => true, 'user' => array('username' => $username, 'token' => $token[0]->getToken()));
            return $this->renderText(json_encode($retval));
        } else {
            $retval = array('success' => false);
            return $this->renderText(json_encode($retval));
        }
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

        $user = sfGuardUserPeer::retrieveByUsername($username);
        //print_r($user);die();
        if (token::check($user, $token)) {
            $retval = array('success' => true, 'user' => $user->getProfile()->expose());
            return $this->renderText(json_encode($retval));
        } else {
            $retval = array('success' => false);
            return $this->renderText(json_encode($retval));
        }
    }

    /**
     * Creates a user.
     * See routes for url, params via POST
     */
    public function executeCreateUser(sfWebRequest $request) {
        $master_token = $request->getParameter('master_token');

        $username = $request->getParameter('username');
        $password = $request->getParameter('password');

        $nombre = $request->getParameter('nombre');
        $apellidos = $request->getParameter('apellidos');
        $email = $request->getParameter('email');

        //print_r($request);die();

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
