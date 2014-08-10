<?php

/**
 * events actions.
 *
 * @package    PhpProject1
 * @subpackage events
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventsActions extends sfActions {

    public function preExecute() {
        if (sfConfig::get('sf_environment') == 'prod')
            $this->getResponse()->setContentType('application/json');
    }

    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'index');
    }
    
    
    /**
     * Unjoins user from events.
     * @param username
     * @param token
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeUnjoin(sfWebRequest $request) {
        $username = $request->getParameter('username');
        $token = $request->getParameter('token');

        $event_key = $request->getParameter('key');

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

        $event = EventPeer::retrieveByKey($event_key);

        //Event does not exists or is not active
        if ($event == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }
        
        $participation = ParticipationPeer::retrieveByPK($user->getId(), $event->getId());
        
        //Association not exists
        if ($participation == null) {
            $retval = array('success' => false, 'error' => 4);
            return $this->renderText(json_encode($retval));
        }

        $participation->delete();
        $retval = array('success' => true, 'participation' => $participation->expose());
        return $this->renderText(json_encode($retval));
    }
    
    /**
     * Joins user to events.
     * @param username
     * @param token
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeJoin(sfWebRequest $request) {
        $username = $request->getParameter('username');
        $token = $request->getParameter('token');

        $event_key = $request->getParameter('key');

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

        $event = EventPeer::retrieveByKey($event_key);

        //Event does not exists or is not active
        if ($event == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }
        
        $checkPart = ParticipationPeer::retrieveByPK($user->getId(), $event->getId());
        
        //Association already exists
        if ($checkPart != null) {
            $retval = array('success' => false, 'error' => 4);
            return $this->renderText(json_encode($retval));
        }

        //Everything OK!, We associate the user
        $participation = new Participation();
        $participation->create($user, $event,$save = 1);
        $retval = array('success' => true, 'participation' => $participation->expose());
        return $this->renderText(json_encode($retval));
    }

    /**
     * Lists Admin's events.
     * @param username
     * @param token
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeListAdmins(sfWebRequest $request) {
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

        $event_list = EventPeer::retrieveByAdmin($user);

        //Everything OK!, we return event info
        $retval = array('success' => true, 'list' => Event::exposeEventList($event_list));
        return $this->renderText(json_encode($retval));
    }

    /**
     * Lists User's events.
     * @param username
     * @param token
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeListUsers(sfWebRequest $request) {
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

        $event_list = EventPeer::retrieveUsersEvent($user);

        //Everything OK!, we return event info
        $retval = array('success' => true, 'list' => Event::exposeEventList($event_list));
        return $this->renderText(json_encode($retval));
    }

    /**
     * Shows all the info from an Event
     * @param username
     * @param token
     * @param key
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeGetInfo(sfWebRequest $request) {
        $username = $request->getParameter('username');
        $token = $request->getParameter('token');

        $event_key = $request->getParameter('key');

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

        $event = EventPeer::retrieveByKey($event_key);

        //Event does not exists or is not active
        if ($event == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }

        //The user is not part of this event
        if (!Participation::checkJoined($user, $event)) {
            $retval = array('success' => false, 'error' => 4);
            return $this->renderText(json_encode($retval));
        }

        //Everything OK!, We return the participants
        $participants = ParticipationPeer::retrieveParticipantsFromEvent($event);
        $program = EntryPeer::retrieveAllFromEvent($event);
        
        //Everything OK!, we return event info
        $retval = array('success' => true);
        $retval['info'] = $event->expose();
        $retval['participants'] = Participation::exposeParticipantList($participants);
        $retval['program']=Entry::exposeEntryList($program);
        return $this->renderText(json_encode($retval));
    }

    /**
     * Shows program from an Event
     * @param username
     * @param token
     * @param event_key
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeShowProgram(sfWebRequest $request) {
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

        //Everything OK!, we validate and log the user in
        $token = TokenPeer::retrieveToken($user);
        $retval = array('success' => true, 'user' => array('username' => $username, 'token' => $token->getToken()));
        return $this->renderText(json_encode($retval));
    }

}
