<?php

/**
 * default actions.
 *
 * @package    PhpProject1
 * @subpackage default
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        //$this->forward('default', 'module');
    }

    public function executeWebJoin(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $event_key = $request->getParameter('key');

        //The user does not exist
        if ($user == null) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $event = EventPeer::retrieveByKey($event_key);

        //Event does not exists or is not active
        if ($event == null) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        $checkPart = ParticipationPeer::retrieveByPK($user->getId(), $event->getId());

        //Association already exists
        if ($checkPart != null) {
            //not invited
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }

        $checkPart = new Participation();
        $checkPart->create($user, $event, 0);
        $checkPart->setActive(1);
        $checkPart->save();
        $retval = array('success' => true, 'participation' => $checkPart->expose());
        //return $this->renderText(json_encode($retval));
    }

}
