<?php

namespace Eventus\MainBundle\Controller;

use Eventus\MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UsersController extends Controller
{
    /**
     * 
     * @return array
     * @View()
     */
    public function getUsersAction(){
        $users= $this->getDoctrine()->getRepository("EventusMainBundle:User")
                ->findAll();
        return array('users'=>$users);
    }
    /**
     * 
     * @param \Eventus\MainBundle\Entity\User $user
     * @return array
     * @View()
     * @ParamConverter("user", class="EventusMainBundle:User")
     */
    public function getUserAction(User $user){
        return array('user' => $user);
    }
}
