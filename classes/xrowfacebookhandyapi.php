<?php

class xrowFacebookHandyAPI implements xrowTwittInterface
{
    public function __construct( $blockName )
    {
        $this->baseURI = 'http://m.facebook.com';
        $this->cookieFile = 'var/log/fb.cookie.txt';
        $this->curloptUserAgent = $_SERVER['HTTP_USER_AGENT'];
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
        $login = $this->accessOptions['Login'];
        $password = $this->accessOptions['Password'];
        
        $curlData = '';
        $curlInfo = '';
        $this->getFrontPage( $curlData, $curlInfo );
        
        $fbLoginUrl = $this->getFormActionURL( $curlData, 'login_form' );
        $fbLoginParams = $this->getInputValuesFromFormContent( $curlData, 'login_form' );
        $fbLoginParams .= '&email=' . $login . '&pass=' . $password;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,                 $fbLoginUrl);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
        curl_setopt($curl, CURLOPT_HEADER,              0);     // output headers above page content
        curl_setopt($curl, CURLINFO_HEADER_OUT,         true);      // also get headers via curlinfo (not necessary)
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,      false);
        curl_setopt($curl, CURLOPT_POST,                1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,          $fbLoginParams);
        curl_setopt($curl, CURLOPT_ENCODING,            "");
        curl_setopt($curl, CURLOPT_COOKIEFILE,          $this->cookieFile );
        curl_setopt($curl, CURLOPT_COOKIEJAR,           $this->cookieFile );
        curl_setopt($curl, CURLOPT_USERAGENT,           $this->curloptUserAgent);
        
        $curlData = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);
        curl_close($curl);
    }
    
    public function getInputValuesFromFormContent( $contents, $form_id )
    {
        $inputParams = array();
        $doc = new DOMDocument();
        if( $doc->loadhtml( $contents ) )
        {
            $xpath = new DOMXpath( $doc );
            foreach( $xpath->query( '//form[@id="'.$form_id.'"]//input' ) as $eInput )
            {
                $inputParams[$eInput->getAttribute( 'name' )] = $eInput->getAttribute( 'value' );
            }
        }
        $tmp = array();
        foreach( $inputParams as $k => $v )
        {
            $tmp[] = $k . '=' . $v;
        }
        $inputParams = join( '&', $tmp );
        return $inputParams;
    }
    
    public function getFormActionURL( $contents, $form_id )
    {
        $inputParams = array();
        $doc = new DOMDocument();
        if( $doc->loadhtml( $contents ) )
        {
            $xpath = new DOMXpath( $doc );
            foreach( $xpath->query( '//form[@id="'.$form_id.'"]' ) as $eForm )
            {
                $inputParams[] = $eForm->getAttribute( 'action' );
            }
        }
        return $inputParams[0];
    }
        
    public function disconnect()
    {
        return true;
    }
    
    private function getFrontPage( &$curlData, &$curlInfo )
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,                 $this->baseURI);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
        curl_setopt($curl, CURLOPT_HEADER,              0);
        curl_setopt($curl, CURLINFO_HEADER_OUT,         true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,      false);
        curl_setopt($curl, CURLOPT_ENCODING,            "");
        curl_setopt($curl, CURLOPT_COOKIEFILE,          $this->cookieFile );
        curl_setopt($curl, CURLOPT_COOKIEJAR,           $this->cookieFile );
        curl_setopt($curl, CURLOPT_USERAGENT,           $this->curloptUserAgent);
        
        $curlData = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);
        curl_close($curl);
    }
    
    public function post( $message )
    {
        $curlData = '';
        $curlInfo = '';
        $this->getFrontPage( $curlData, $curlInfo );
        
        $fbLoginUrl = $this->baseURI . $this->getFormActionURL( $curlData, 'composer_form' );
        $fbPostParams = $this->getInputValuesFromFormContent( $curlData, 'composer_form' );
        $fbPostParams .= '&status=' . $message;
        
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,                 $fbLoginUrl);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
        curl_setopt($curl, CURLOPT_HEADER,              0);     // output headers above page content
        curl_setopt($curl, CURLINFO_HEADER_OUT,         true);      // also get headers via curlinfo (not necessary)
        curl_setopt($curl, CURLOPT_POST,                1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,      false);
        curl_setopt($curl, CURLOPT_POSTFIELDS,          $fbPostParams);
        curl_setopt($curl, CURLOPT_ENCODING,            "");
        curl_setopt($curl, CURLOPT_COOKIEFILE,          $this->cookieFile );
        curl_setopt($curl, CURLOPT_COOKIEJAR,           $this->cookieFile );
        curl_setopt($curl, CURLOPT_USERAGENT,           $this->curloptUserAgent);
        
        $curlData = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);
        
        curl_close($curl);
    }
    
    private $ini;
    private $connection;
    private $accessOptions;
    private $accountBlock;
    private $baseURI;
    private $cookieFile;
    private $curloptUserAgent;
}


?>