<?php
/**
 * DokuWiki Plugin authmaglab (Auth Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Kia Kroas <admin@maglaboratory.org>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class auth_plugin_authmaglab extends DokuWiki_Auth_Plugin {
  protected $current_user = NULL;
  
    public function __construct() {
        parent::__construct(); // for compatibility

        // FIXME set capabilities accordingly
        $this->cando['addUser']     = false; // can Users be created?
        $this->cando['delUser']     = false; // can Users be deleted?
        $this->cando['modLogin']    = false; // can login names be changed?
        $this->cando['modPass']     = false; // can passwords be changed?
        $this->cando['modName']     = false; // can real names be changed?
        $this->cando['modMail']     = false; // can emails be changed?
        $this->cando['modGroups']   = false; // can groups be changed?
        $this->cando['getUsers']    = false; // can a (filtered) list of users be retrieved?
        $this->cando['getUserCount']= false; // can the number of users be retrieved?
        $this->cando['getGroups']   = false; // can a list of available groups be retrieved?
        $this->cando['external']    = false; // does the module do external auth checking?
        $this->cando['logout']      = false; // can the user logout again? (eg. not possible with HTTP auth)

        // FIXME intialize your auth system and set success to true, if successful
        $this->success = true;
    }


    /**
     * Log off the current user [ OPTIONAL ]
     */
    //public function logOff() {}

    /**
     * Check user+password
     *
     * May be ommited if trustExternal is used.
     *
     * @param   string $user the user name
     * @param   string $pass the clear text password
     * @return  bool
     */
    public function checkPass($user, $pass) {
      return ($this->current_user and $user == $this->current_user['id']);
    }

    
    public function authenticate(){
      $env = Slim\Environment::Mock($_SERVER);
      $env = Slim\Environment::getInstance();
      global $app;
      $app->request = new Slim\Http\Request($env);
      $app->response = new Slim\Http\Response();

      $current_user = member_authenticate_by_auth($app->getCookie('auth'));
      if(!$current_user){ header('Location: /members'); die(); }
      $this->current_user = $current_user;
      return $current_user;
    }
    /**
     * Return user info
     *
     * Returns info about the given user needs to contain
     * at least these fields:
     *
     * name string  full name of the user
     * mail string  email addres of the user
     * grps array   list of groups the user is in
     *
     * @param   string $user the user name
     * @return  array containing user data or false
     */
    public function getUserData($user) {
      if(is_array($user) and $user['id']){
        $user['name'] = "${user['first_name']} ${user['last_name']}";
        $user['mail'] = $user['email'];
        $user['grps'] = explode(',', $user['role']);
        array_push($user['grps'], 'user');
        return $user;
      } elseif($this->current_user and $this->current_user['id'] == $user){
        return $this->getUserData($this->current_user);
      }
      return false;
    }
    
    /**
     * Retrieve groups [implement only where required/possible]
     *
     * Set getGroups capability when implemented
     *
     * @param   int $start
     * @param   int $limit
     * @return  array
     */
    //public function retrieveGroups($start = 0, $limit = 0) {
        // FIXME implement
    //    return array();
    //}

    /**
     * Return case sensitivity of the backend
     *
     * When your backend is caseinsensitive (eg. you can login with USER and
     * user) then you need to overwrite this method and return false
     *
     * @return bool
     */
    public function isCaseSensitive() {
        return true;
    }

    /**
     * Sanitize a given username
     *
     * This function is applied to any user name that is given to
     * the backend and should also be applied to any user name within
     * the backend before returning it somewhere.
     *
     * This should be used to enforce username restrictions.
     *
     * @param string $user username
     * @return string the cleaned username
     */
    public function cleanUser($user) {
        return $user;
    }

    /**
     * Sanitize a given groupname
     *
     * This function is applied to any groupname that is given to
     * the backend and should also be applied to any groupname within
     * the backend before returning it somewhere.
     *
     * This should be used to enforce groupname restrictions.
     *
     * Groupnames are to be passed without a leading '@' here.
     *
     * @param  string $group groupname
     * @return string the cleaned groupname
     */
    public function cleanGroup($group) {
        return $group;
    }

}

// vim:ts=4:sw=4:et:
