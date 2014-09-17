<?php

class createUserTokenTask extends sfBaseTask {

    protected function configure() {
        // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('username', sfCommandArgument::REQUIRED, 'username'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
                // add your own options here
        ));

        $this->namespace = 'eventus';
        $this->name = 'createUserToken';
        $this->briefDescription = 'Creates a Token for the given user.';
        $this->detailedDescription = $this->briefDescription;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // add your code here
        $user = sfGuardUserPeer::retrieveByUsername($arguments['username']);
        if($user != NULL){
            Token::createTokenForUser($user);
            $this->logSection($this->namespace,'Token for user '.$user->getUsername().' created!');
        }
    }

}
