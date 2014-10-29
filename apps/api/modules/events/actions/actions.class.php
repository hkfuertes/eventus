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

        if ($user->getId() == $event->getAdminId()) {
            //we remove also the event
            $event->delete();
            //we might notify via email and zip the pictures.
        }
        $retval = array('success' => true, 'participation' => $participation->expose());
        return $this->renderText(json_encode($retval));
    }

    public function executeJoinGET(sfWebRequest $request) {
        $username = $request->getParameter('who');
        $event_key = $request->getParameter('key');
        //$who = $request->getParameter('who',$username);
        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($request->getParameter('app_token'))) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = sfGuardUserPeer::retrieveByUsername($username);

        //The user does not exist
        if ($user == null) {
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
            if ($checkPart->getActive() == 0) {
                $checkPart->setActive(1);
                $checkPart->save();
                $retval = array('success' => true, 'participation' => $checkPart->expose());
                return $this->renderText(json_encode($retval));
            } else {
                $retval = array('success' => false, 'error' => 4);
                return $this->renderText(json_encode($retval));
            }
        } else {
            //not invited
            $retval = array('success' => false, 'error' => 5);
            return $this->renderText(json_encode($retval));
        }
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
        //$who = $request->getParameter('who',$username);
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
        $participation->create($user, $event, 0);
        $participation->setActive(1);
        $participation->save();
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
        $who = $request->getParameter('who');
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

        $wuser = sfGuardUserPeer::retrieveByUsername($who);
        //The user asked does not exists.
        if ($wuser == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }

        $event_list = EventPeer::retrieveByAdmin($wuser);

        //Everything OK!, we return event info
        $retval = array('success' => true, 'events' => Event::exposeEventKeys($event_list));
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
        $who = $request->getParameter('who');
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

        $wuser = sfGuardUserPeer::retrieveByUsername($who);
        //The user asked does not exists.
        if ($wuser == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }

        $event_list = EventPeer::retrieveUsersEvent($wuser);

        //Everything OK!, we return event info
        $retval = array('success' => true, 'events' => Event::exposeEventKeys($event_list));
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
        $retval['program'] = Entry::exposeEntryList($program);
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

    /**
     * Shows program from an Event
     * @param username
     * @param token
     * @param event_key
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeInvite(sfWebRequest $request) {
        sfConfig::set('sf_escaping_strategy', false);

        $username = $request->getParameter('username');
        $token = $request->getParameter('token');
        $app_token = $request->getParameter('app_token');
        $event_key = $request->getParameter('key');

        $invitation_text = $request->getParameter('invitation_body', null);
        $usermail = $request->getParameter('who'); //Array
        //$who = $request->getParameter('who',$username);
        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($app_token)) {
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

        //print_r($usermail); die($usermail);
        //generate mail
        // send an email to the affiliate
        $url = array();
        foreach ($usermail as $email) {
            $user = sfGuardUserProfilePeer::retrieveUserByEmail($email);
            if (Participation::checkJoined($user, $event)) {
                //$retval = array('success' => false, 'error' => 4);
                //return $this->renderText(json_encode($retval));
                $error4[] = $user->getUsername();
            } else {
                if ($user != null) {
                    $url[$user->getUsername()] = $this->generateUrl('event_join_user_get', array('app_token' => $app_token, 'key' => $event->getKey(), 'who' => $user->getUsername()), true);
                    $participation = new Participation();
                    $participation->create($user, $event);
                }
            }
            //$body="Hey, you were invited to an eventus on Eventus, click <a href='".$url."'>here</a> to join.";
            //$this->sendMail($email, $body, "Eventus Invitation");
        }
        if (count($error4) > 0) {
            $retval = array('success' => true, 'invitation' => array($event->getKey() => $url), 'error' => array('code' => 4, 'users' => $error4));
        } else {
            $retval = array('success' => true, 'invitation' => array($event->getKey() => $url));
        }
        return $this->renderText(json_encode($retval));
    }

    private function sendMail($address, $body, $subject) {
        $message = $this->getMailer()->compose(
                array('eventus@noreply.eventus.net' => 'Eventus'), $address, $subject, $body
        );
        $this->getMailer()->send($message);
    }

    /**
     * Creates new Event
     * @param username
     * @param token
     * @param app_token
     * 
     * @param event_data
     * See routes for url, params via POST
     */
    public function executeSave(sfWebRequest $request) {
        /**
         * event_data:
         *  - name
         *  - place
         *  - date
         *  - event_type_id
         *  - admin_id = username (the one that calls the event)
         * 
         *  - if editing, event_data[key] will be passed.
         * 
         * We create the event. Then with the event created we prompt to create
         * the timetable, and then we invite people via email.
         * 
         * we have to return the event_key to concatenate the ops.
         */
        sfConfig::set('sf_escaping_strategy', false);

        $username = $request->getParameter('username');
        $token = $request->getParameter('token');
        $app_token = $request->getParameter('app_token');


        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($app_token)) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = sfGuardUserPeer::retrieveByUsername($username);

        //The user has wrong token
        if (!Token::check($user, $token)) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        /**
         * The app is now authorised and the user too.
         * We create the event.
         */
        $event_data = $request->getParameter('event_data', null); //Array
        if (!is_array($event_data))
            $event_data = json_decode($event_data);

        //No data passed.
        if ($event_data == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }

        if (isset($event_data['key'])) {
            //We are editing.
            $event = EventPeer::retrieveByKey($event_data['key']);
            $participation = ParticipationPeer::retrieveByPK($user->getId(), $event->getId());

            //Event does not exists or is not active
            if ($event == null) {
                $retval = array('success' => false, 'error' => 4);
                return $this->renderText(json_encode($retval));
            }

            if ($user->getId() != $event->getAdminId()) {
                $retval = array('success' => false, 'error' => 5);
                return $this->renderText(json_encode($retval));
            }
        } else
            $event = new Event($user);

        $event->setName($event_data['name']);
        $event->setPlace($event_data['place']);
        $event->setDate($event_data['date']);
        $event->setEventTypeId($event_data['event_type_id']);
        $event->save();

        // IF new Event... firs we create the event and then we associate.
        if (isset($event_data['key'])) {
            $participation = new Participation();
            $participation->create($user, $event, 0);
            $participation->setActive(1);
            $participation->save();
        }


        //We return the session_key
        $retval = array('success' => true, 'event' => $event->expose());
        return $this->renderText(json_encode($retval));
    }

    public function executeSaveProgram(sfWebRequest $request) {
        /*
         * Datos que espera recibir:
         * event_program['key'][] = array('time'=>time, 'act'=>act);
         */
        $username = $request->getParameter('username');
        $token = $request->getParameter('token');
        $app_token = $request->getParameter('app_token');


        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($app_token)) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = sfGuardUserPeer::retrieveByUsername($username);

        //The user has wrong token
        if (!Token::check($user, $token)) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        /**
         * The app is now authorised and the user too.
         * We create the event.
         */
        $event_program = $request->getParameter('event_program', null); //Array
        //print_r($event_program);
        //No data passed.
        if ($event_program == null) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }

        //we have event program
        foreach ($event_program as $key => $program) {
            $event = EventPeer::retrieveByKey($key);
            EntryPeer::clearAllFromEvent($event);

            //Event does not exists or is not active
            if ($event == null) {
                $retval[] = array('error' => 4,'event'=>$key, 'entry' => $program);
            }
            //The user is not part of this event
            if (!Participation::checkJoined($user, $event)) {
                $retval[] = array('error' => 5,'event'=>$key, 'entry' => $program);
            }
            foreach ($program as $nEntry) {
                $entry = new Entry();
                $entry->setEvent($event);
                $entry->setTime($event->getDate('Y-m-d') . " " . $nEntry['time']);
                $entry->setAct($nEntry['act']);
                $entry->save();
            }
        }

        if (isset($retval)) {
            return $this->renderText(json_encode(array('succes' => false, 'errors' => $retval)));
        }

        $nProgram = EntryPeer::retrieveAllFromEvent($event);
        return $this->renderText(json_encode(array('success' => true, 'program' => Entry::exposeEntryList($nProgram))));
    }

}
