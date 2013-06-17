<?php

class xrowTwittType extends eZDataType
{
    const DATA_TYPE_STRING = "xrowtwitt";
    const POST_VARIABLE = '_xrowtwitt_text_';

    function __construct()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, 'xrowtwitt',
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'text' ) ) );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $objectPublished = $contentObjectAttribute->attribute( 'data_int' );
        if( $objectPublished != 1 )
        {
            if ( $http->hasPostVariable( $base . self::POST_VARIABLE . $contentObjectAttribute->attribute( 'id' ) ) )
            {
                $data = $http->postVariable( $base . self::POST_VARIABLE . $contentObjectAttribute->attribute( 'id' ) );
                if ( $data == "" )
                {
                    if ( $contentObjectAttribute->validateIsRequired() )
                    {
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }
            }
            elseif ( $contentObjectAttribute->validateIsRequired() )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $objectPublished = $contentObjectAttribute->attribute( 'data_int' );
        if( $objectPublished != 1 && $http->hasPostVariable( $base . self::POST_VARIABLE . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . self::POST_VARIABLE . $contentObjectAttribute->attribute( "id" ) );
            $contentObjectAttribute->setAttribute( "data_text", $data );
        }
        return true;
    }
    
    
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $objectPosted = $contentObjectAttribute->attribute( 'data_int' );
        if( $objectPosted != 1 )
        {
            $http = eZHTTPTool::instance();
            $twitterVariableName = 'ContentObjectAttribute_xrowtwitt_twitter_' . $contentObjectAttribute->attribute( 'id' );
            $facebookVariableName = 'ContentObjectAttribute_xrowtwitt_facebook_' . $contentObjectAttribute->attribute( 'id' );
            $twitterBlock = $http->hasPostVariable( $twitterVariableName )?$http->postVariable( $twitterVariableName ):false;
            $facebookBlock = $http->hasPostVariable( $facebookVariableName )?$http->postVariable( $facebookVariableName ):false;
            $hasContent = $contentObjectAttribute->hasContent();
                        
            if ( $hasContent && ( $twitterBlock != -1 || $facebookBlock != -1 ) )
            {
                $attributeContent = $contentObjectAttribute->attribute( 'content' );
                $mainNode = false;
                foreach ( array_keys( $publishedNodes ) as $publishedNodeKey )
                {
                    $publishedNode = $publishedNodes[$publishedNodeKey];
                    if ( $publishedNode->attribute( 'is_main' ) )
                    {
                        $mainNode = $publishedNode;
                        break;
                    }
                }
                if ( $mainNode )
                {
                    $url = eZSys::serverURL() . eZSys::indexDir() . '/' . $mainNode->attribute( 'url_alias' );
                    $shortLink = new xrowShortLink( $url );
                }
                if( $shortLink->length() )
                {
                    $postContent = substr( $attributeContent, 0, ( 139-$shortLink->length() ) ) . ' ' . $shortLink->outputURI;
                }
                else
                {
                    $postContent= substr( $attributeContent, 0, 140 );
                }
                
                if( $twitterBlock != -1 )
                {
                    $connection = new xrowTwitterAPI( $twitterBlock );
                    $connection->connect();
                    $connection->post( $postContent );
                }
                
                if( $facebookBlock != -1 )
                {
                    $connection = new xrowFacebookHandyAPI( $facebookBlock );
                    $connection->connect();
                    $connection->post( $postContent );
                }
                $contentObjectAttribute->setAttribute( 'data_int', 1 );
                $contentObjectAttribute->store();
            }
        }
    }
    

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }


    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    /*!
     Returns the text.
    */
    function title( $data_instance, $name = null )
    {
        return $data_instance->attribute( "data_text" );
    }

    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return false;
    }
}

eZDataType::register( xrowTwittType::DATA_TYPE_STRING, "xrowTwittType" );

?>
