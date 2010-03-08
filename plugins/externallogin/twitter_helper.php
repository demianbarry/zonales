<?php 

defined('_JEXEC') or die('Restricted access');

jimport('twitter.EpiCurl');
jimport('twitter.EpiTwitter');

class TwitterHelper {
  
  private $consumer;
  private $token;

  function __construct($provider = 'Twitter') {
    $this->db = JFactory::getDBO();
    $this->db->setQuery('select p.apikey, p.secretkey as secret from #__providers p where p.name=' . $this->db->Quote($provider));
    $consumer = $this->db->loadObject();
    if (!$consumer) throw new Exception('ComTwitterNotInstalledOrConfigured');
    $this->consumer = $consumer;
  }

  function getJoomlaUserMapping($id) {
    $this->db->setQuery(
      sprintf('select * from #__twitter_mapping where userid="%s"', 
        $this->db->getEscaped($id)
      )
    );
    $mapping = $this->db->loadObject();
    return $mapping;
  }

  function getUserMapping() {
    $twitterInfo = $this->getCredentials();
    $this->db->setQuery(
      sprintf('select * from #__twitter_mapping where twitterid="%s"', 
        $this->db->getEscaped($twitterInfo->id)
      )
    );
    $mapping = $this->db->loadObject();
    return $mapping;
  }

  function setUserMapping() {
    $twitterInfo = $this->getCredentials();
    $twitter_userid = $twitterInfo->id;
		$user = JFactory::getUser();	
    return $this->db->execute(sprintf("insert into #__twitter_mapping values(DEFAULT, '%s', '%s')", $twitter_userid, $user->id));
  }

  function getCredentials() {
      $db = JFactory::getDBO();
    if ( isset($_SESSION['com_twitter_credentials']) && 
          $_SESSION['com_twitter_credentials']->oauth_token == $_COOKIE['oauth_token'] &&
          $_SESSION['com_twitter_credentials']->oauth_token_secret == $_COOKIE['oauth_token_secret'] &&
          $_SESSION['com_twitter_credentials']->twitterInfo->timeout > time()
    ) {
      $twitterInfo = $_SESSION['com_twitter_credentials']->twitterInfo;
      $this->logme($db, 'getCredentials | las credenciales ya estaban seteadas');
    } else {
      try {
        $twitterInfo = null;
        $twitterObj = new EpiTwitter(
          $this->consumer->key, 
          $this->consumer->secret, 
          $this->token->oauth_token, 
          $this->token->oauth_token_secret
        );
        $twitterInfo = $twitterObj->get_accountVerify_credentials();
        
        $ti = new stdClass();
        $ti->timeout = time() + 20 * 60; //(20 minutes of timeout)
        $ti->name = $twitterInfo->name;
        $ti->screen_name = $twitterInfo->screen_name;
        $ti->status = $twitterInfo->status;
        $ti->id = $twitterInfo->id;
        $ti->profile_image_url = $twitterInfo->profile_image_url;
        
        $twitter_credentials = new stdClass();
        $twitter_credentials->twitterInfo = $ti;
        $twitter_credentials->oauth_token = $this->token->oauth_token;
        $twitter_credentials->oauth_token_secret = $this->token->oauth_token_secret;

        $_SESSION['com_twitter_credentials'] = $twitter_credentials;
        $twitterInfo = $ti;
      } catch(Exception $e){
        throw $e;
      }
    }
    return $twitterInfo;
  }

  function areCookiesSet() {
    if ($_COOKIE['oauth_token'] && $_COOKIE['oauth_token_secret']) {
      return true;
    } else {
      $this->clearCookies();
      return false;
    }
  }

  function clearCookies() {
    setcookie('oauth_token', '', 1);
    setcookie('oauth_token_secret', '', 1);
  }

  function getAuthenticateUrl() {
    $twitterObj = new EpiTwitter($this->consumer->key, $this->consumer->secret);
    return $twitterObj->getAuthenticateUrl();
  }

  function doLogin($oauthToken) {
    $twitterObj = new EpiTwitter($this->consumer->key, $this->consumer->secret);
    $twitterObj->setToken($oauthToken);
    $token = $twitterObj->getAccessToken();
    $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);

    // save to cookies
    setcookie('oauth_token', $token->oauth_token, 0, '/' );
    setcookie('oauth_token_secret', $token->oauth_token_secret, 0, '/');
    $this->token = $token;
    //return $this->getCredentials();
    return $twitterObj->get_accountVerify_credentials();
  }

    private function logme($db,$message) {
        $query='insert into #__logs(info,timestamp) values ("' .
            $message . '","' . date('Y-m-d h:i:s') . '")';
        $db->setQuery($query);
        $db->query();
    }
}
?>
