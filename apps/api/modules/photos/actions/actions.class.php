<?php

/**
 * photos actions.
 *
 * @package    PhpProject1
 * @subpackage photos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class photosActions extends sfActions {

    public function preExecute() {
        if (sfConfig::get('sf_environment') == 'prod')
            $this->getResponse()->setContentType('application/json');
    }

    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'index');
    }

    /**
     * Gets Photos from event
     * @param username
     * @param token
     * @param key
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeGetEventPhotos(sfWebRequest $request) {
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

        //OK! we retrieve the photos.
        $photolist = PhotoPeer::retrieveByEventId($event->getId());

        $retval = array('success' => true);
        $retval['event_key'] = $event->getKey();
        $retval['photos'] = Photo::exposePhotoList($photolist);
        return $this->renderText(json_encode($retval));
    }

    /**
     * gets Photos from user
     * @param username
     * @param token
     * @param app_token
     * See routes for url, params via POST
     */
    public function executeGetUserPhotos(sfWebRequest $request) {
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

        //OK! we retrieve the photos.
        $photolist = PhotoPeer::retrieveByUserId($user->getId());

        $retval = array('success' => true);
        $retval['user'] = $user->getUsername();
        $retval['photos'] = Photo::exposePhotoList($photolist);
        return $this->renderText(json_encode($retval));
    }

    /**
     * Upload a Photo
     * @param username
     * @param token
     * @param app_token
     * @param key
     * @param (file) photo
     * See routes for url, params via POST
     */
    public function executeUploadPhoto(sfWebRequest $request) {
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

        $uploaddir = sfConfig::get('app_photos_folder');
        $filename = basename($_FILES['photo']['name']);

        //creamos nuevo fichero.
        $p = new Photo();
        $p->setEvent($event);
        $p->setUserId($user->getId());
        $p->setPath($uploaddir . sha1($filename . $p->getUploadedAt()));
        $p->setFilename($filename);

        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($_FILES['photo']['tmp_name']);

        if (in_array($detectedType, $allowedTypes)) {
            //We move the file to the folder.
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $p->getPath())) {
                //Error 5, file not moved.
                $retval = array('success' => false, 'error' => 5);
                return $this->renderText(json_encode($retval));
            }
        } else {
            //Error 6, not an image.
            $retval = array('success' => false, 'error' => 6);
            return $this->renderText(json_encode($retval));
        }

        //If The file is uploaded, we save the mysql data and return an ok!
        $p->save();

        $retval = array('success' => true);
        $retval['user'] = $user->getUsername();
        $retval['event'] = $event->getKey();
        $retval['photo'] = $p->expose();
        return $this->renderText(json_encode($retval));
    }

    public function executeShowPhoto(sfWebRequest $request) {
        $photo_id = $request->getParameter('photo_id');
        $event_key = $request->getParameter('event_key');
        $photo = PhotoPeer::retrieveByPK($photo_id);
        
        //The app is not registered or not active.
        if (!AppTokenPeer::checkExistAndActive($request->getParameter('app_token'))) {
            $retval = array('success' => false, 'error' => 1);
            return $this->renderText(json_encode($retval));
        }

        $user = $this->getUser()->getGuardUser();
        
        $event = EventPeer::retrieveByKey($event_key);

        //Event does not exists or is not active
        if ($event == null) {
            $retval = array('success' => false, 'error' => 2);
            return $this->renderText(json_encode($retval));
        }

        //The user is not part of this event
        if (!Participation::checkJoined($user, $event)) {
            $retval = array('success' => false, 'error' => 3);
            return $this->renderText(json_encode($retval));
        }

        $file = $photo->getPath();
        $type = 'image/*';
        header('Content-Type:'.$type);
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: inline; filename="'.$photo->getFilename().'"');
        readfile($file);
        return null;
    }

}
