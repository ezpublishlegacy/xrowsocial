<?php

$http = eZHTTPTool::instance();
$successfull = true;

if( $http->hasVariable('account') )
{
    $blockName = $http->variable('account');
    $connection = new xrowFacebookAPI( $blockName );
    $connection->connect();
    $loginStatus = $connection->checkLogin();
}



$tpl = eZTemplate::factory();
$tpl->setVariable( 'login_status', $loginStatus );

$Result = array();
$Result['content'] = $tpl->fetch( "design:xrowtwitt/callback.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'Thank you' ) );

?>