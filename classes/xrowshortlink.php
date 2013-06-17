<?php

class xrowShortLink
{
    public function __construct( $inputURI )
    {
        $this->inputURI = $inputURI;
        $ini = eZINI::instance( 'xrowtwitt.ini' );
        $this->username = $ini->variable( 'xrowTwittBitlyShortLink', 'Username' );
        $this->apikey = $ini->variable( 'xrowTwittBitlyShortLink', 'APIKey' );
        $this->enabled = $ini->variable( 'xrowTwittBitlyShortLink', 'AppendShortURL' )=='enabled';
        if( !$this->username || !$this->username )
        {
            $this->enabled = false;
        }
        $this->create();
    }
    
    public function create()
    {
        if( !$this->enabled )
        {
            return false;
        }
        
        $url = 'http://api.bit.ly/shorten?version='.$this->version.'&longUrl='.urlencode($this->inputURI).'&login='.$this->username.'&apiKey='.$this->apikey.'&format='.$this->format;
        $response = file_get_contents($url);
        //parse depending on desired format
        if( strtolower( $this->format ) == 'json' )
        {
            $json = @json_decode( $response, true );
            $this->outputURI = $json['results'][$this->inputURI]['shortUrl'];
        }
        else //xml
        {
            $xml = simplexml_load_string($response);
            $this->outputURI = 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
        }
        return $this->outputURI;
    }
    
    public function length()
    {
        return strlen( $this->outputURI );
    }
    
    public $inputURI;
    public $outputURI;
    
    private $enabled;
    private $username;
    private $apikey;
    private $version = '2.0.1';
    private $format = 'json';
}

?>