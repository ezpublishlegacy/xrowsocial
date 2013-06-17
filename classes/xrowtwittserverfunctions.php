<?php

class xrowTwittServerFunctions
{
    public static function checklogin( $args )
    {
        $returnValues = array( 
            'status' => 0,
            'error_message' => ''
        );
        $accountType = isset($args[0])?$args[0]:false;
        $accountBlock = isset($args[1])?$args[1]:false;
        
        switch( $accountType )
        {
            case 'twitter':
            {
                $connection = new xrowTwitterAPI( $accountBlock );
                
            }break;
            case 'facebook':
            {
                $connection = new xrowFacebookAPI( $accountBlock );
                if( $connection instanceof xrowTwittInterface )
                {
                    $connection->connect();
                    $loginSettings = $connection->checkLogin();
                    return $loginSettings;
                }
                else
                {
                    $returnValues['error_message'] = 'Connection to Facebook Failed';
                }
            }break;
            default:
            {
                $returnValues['error_message'] = 'unknown type: ' . $accountType;
            }break;
        }
        
        return $returnValues;
    }
    
}
?>
