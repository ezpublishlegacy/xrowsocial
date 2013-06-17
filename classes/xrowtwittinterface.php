<?php

interface xrowTwittInterface
{
    const INIBLOCKPREFIX = 'xrowTwitt_';
    
    public function connect(); //opens a connection to the api
    public function disconnect(); //disconnect from the api
    public function post( $message ); //post a message on the choosen api
}

?>