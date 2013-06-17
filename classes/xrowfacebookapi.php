<?php

class xrowFacebookAPI implements xrowTwittInterface
{
    public function __construct( $blockName )
    {
        $this->ini = eZINI::instance( 'xrowtwitt.ini' );
        if( $this->ini->hasGroup( xrowTwittInterface::INIBLOCKPREFIX . $blockName ) )
        {
            $this->accountBlock = $blockName;
            $this->accessOptions = $this->ini->group( xrowTwittInterface::INIBLOCKPREFIX . $blockName );
        }
        else
        {
            eZDebug::writeError( "Undefined group: '$blockName' in " . $this->ini->FileName, __METHOD__ );
            return null;
        }
    }

    
    public function connect()
    {
        $appid = $this->accessOptions['APPID'];
        $apisecret = $this->accessOptions['APISecret'];
        $this->connection = new Facebook(array(
            'appId' => $appid,
            'secret' => $apisecret,
            'cookie' => true,
        ));
    }
    
    public function checkLogin()
    {
        $returnValues = array();
        $session = $this->connection->getSession();
        if( $session )
        {
            try
            {
                $uid = $this->connection->getUser();
                $me = $this->connection->api('/me');
            }
            catch( FacebookApiException $e )
            {
                $returnValues = array( 'status' => -1, 'status_message' => 'Login failed' );
            }
        }

        if( !$me )
        {
            $callback_uri = eZSys::serverURL() . eZSys::indexDir() . '/xrowtwitt/callback?type=facebook&account=' . $this->accountBlock;
            $this->connection->setCallBackUrl( $callback_uri );
            $loginUrl = $this->connection->getLoginUrl(array('req_perms' => 'email,sms,read_stream,publish_stream'));
            $returnValues = array( 'status' => 0, 'status_message' => $loginUrl );
        }
        else
        {
            $returnValues = array( 'status' => 1, 'status_message' => 'OK' );
        }
        return $returnValues;
    }
    
    public function disconnect()
    {
        return true;
    }
    
    public function post( $message )
    {
        $profileID = $this->accessOptions['ProfileID'];
        $loginStatus = $this->checkLogin();
        if( $loginStatus['status'] == 1 )
        {
            $result = $this->connection->api(
                "/$profileID/feed/",
                'post',
                array( 'message' => $message )
            );
        }
        return true;
    }
    
    /*
        echo "<pre>";
    $session = $this->connection->getSession();
    var_dump($session);
    
    $loginUrl = $this->connection->getLoginUrl();
    var_dump($loginUrl);
    */
    //$url = "https://graph.facebook.com/oauth/access_token?client_id=$appid&client_secret=$apisecret&grant_type=client_credentials";
    
    //$access_token = explode( '=', $access_token );
    //$access_token = $access_token[1];
    //var_dump( $code );
    //code = 89tBdLNAdKHAoff7ziDEchPZVZyvKv7VVgMJWI0bgNk.eyJpdiI6IktfaWM4ZjVZeFVHT2dpRmM0czJReWcifQ.zO7_NkH0qZ_B1K39C2EksiOZT9kzWvhV4oO0BKgZMD8QWdq2B8SH0sp7kHbNCi3K_Ad8okxYU554mcq-z0MESka-ddkLl6lWASlCXnIbR7DIQxCXqaUSTextknS2fjH69HG-gXlOe3K-Um4-IRV8oA
    /*
     $getLinkCode = "https://graph.facebook.com/oauth/authorize".
          "?client_id=$appid".
          "&redirect_uri=http://matterhorn.web3.all2e.com/".
          "&scope=publish_stream";
    $code = file_get_contents( $getLinkCode );
    //var_dump( $code );
    
    */
    /*
    $getLinkToken = "https://graph.facebook.com/oauth/access_token".
          "?client_id=$appid".
          "&redirect_uri=http://matterhorn.web3.all2e.com/ezflow_site_admin/xrowtwitt/redirect/".
          "&client_secret=$apisecret".
          "&code=$code";
    
    $access_token = file_get_contents( $getLinkToken );

    var_dump($access_token);
    */
    
    //var_dump( '/me?'.$access_token );
    
    //echo "user";
    //$user = $this->connection->api('/me?access_token='.$access_token);
    
    //var_dump($user);
    
    /*
    
    $result = $this->connection->api(
        "/me/feed/",
        'post',
        array('access_token' => $access_token, 'message' => 'Playing around with FB Graph..')
    );
            
    
    var_dump($result);
    
    die();
    //$session = $this->connection->getSession();
    //var_dump( $session );
    
    //$loginUrl = $this->connection->getLoginUrl();
    //var_dump( $loginUrl );
    
    //echo "hier";
    
    //$naitik = $this->connection->api('/me');
    //var_dump( $naitik );

    die();*/
    
    private $ini;
    private $connection;
    private $accessOptions;
    private $accountBlock;
}


?>