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

}
