<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Eventus\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Eventus\MainBundle\Entity\User;

/**
 * Description of LoadUserData
 *
 * @author hkfuertes
 */
class LoadUserData implements FixtureInterface {
    
    public function load(ObjectManager $manager) {
        $alice = new User();
        $alice->setUsername("Alice");
        $alice->setPassword("pasword");
        $alice->setEmail("alice@email");
        
        $bob = new User();
        $bob->setUsername("Bob");
        $bob->setPassword("password");
        $bob->setEmail("bob@email");
        
        $manager->persist($alice);
        $manager->persist($bob);
        
        $manager->flush();
    }

//put your code here
}
