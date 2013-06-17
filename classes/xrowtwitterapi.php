<?php

class xrowTwitterAPI implements xrowTwittInterface
{
    public function __construct( $blockName )
    {
        $this->ini = eZINI::instance( 'xrowtwitt.ini' );
        if( $this->ini->hasGroup( xrowTwittInterface::INIBLOCKPREFIX . $blockName ) )
        {
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
        $this->connection = new TwitterOAuth(
            $this->accessOptions['ConsumerKey'],
            $this->accessOptions['ConsumerSecret'],
            $this->accessOptions['OAuthToken'],
            $this->accessOptions['OAuthTokenSecret']
        );
    }
    
    public function disconnect()
    {
        return true;
    }
    
    public function post( $message )
    {
        $res = $this->connection->post( 'statuses/update', array( 'status' => $message ) );
    }
    
    public function getAuthorizeURL()
    {
        $requestToken = $this->connection->getRequestToken();
        $authorizeURL = $this->connection->getAuthorizeURL( $requestToken['oauth_token'] );
        return $authorizeURL;
    }
    
    private $ini;
    private $connection;
    private $accessOptions;
}


?>