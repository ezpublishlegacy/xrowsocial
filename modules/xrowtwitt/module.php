<?php

$Module = array( 'name' => 'xrowtwitt - Module' );

$ViewList = array();

$ViewList['redirect'] = array( 'functions' => array( 'redirect' ),
                               'script' => 'redirect.php',
                               'params' => array() );

$ViewList['callback'] = array( 'functions' => array( 'callback' ),
                               'script' => 'callback.php',
                               'params' => array() );

$FunctionList['redirect'] = array();
$FunctionList['callback'] = array();



?>
