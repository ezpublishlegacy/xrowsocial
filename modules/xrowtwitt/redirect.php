<?php
/*
$app_id = "171159026275680";
$app_secret = "42f69225112655da870da3c2ee6f2aae";
$my_url = "http://matterhorn.web3.all2e.com/ezflow_site_admin/xrowtwitt/redirect/";
$code = $_REQUEST["code"];




if( empty( $code ) )
{
    $getLinkCode ='https://graph.facebook.com/oauth/authorize'.
              "?client_id=$app_id".
              "&redirect_uri=$my_url".
              "&scope=publish_stream";
    header( 'Location: '. $getLinkCode );
}

echo "<pre>";
var_dump($_REQUEST);
$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
. $app_id . "&amp;redirect_uri=" . urlencode($my_url)
. "&amp;client_secret=" . $app_secret
. "&amp;code=" . $code;
var_dump($code);

$getLinkToken = "https://graph.facebook.com/oauth/access_token".
              "?client_id=$app_id".
              "&redirect_uri=$my_url".
              "&client_secret=$app_secret".
              "&code=$code";
        
$access_token = file_get_contents( $getLinkToken );

//$access_token = file_get_contents($token_url);
var_dump($access_token);
$graph_url="https://graph.facebook.com/me/permissions?".$access_token;
echo "graph_url=" . $graph_url . "<br />";
$user_permissions = json_decode(file_get_contents($graph_url));
var_dump($user_permissions);
*/

/*
$fb = new Facebook( '171159026275680', '42f69225112655da870da3c2ee6f2aae', true );

$username = 'marketingblog@gmx.de';
$password = 'ebeldied';

$curloptUserAgent = 'Mozilla/5.0 (X11; U; Linux i686; de; rv:1.9.1.9) Gecko/20100401 Ubuntu/9.10 (karmic) Firefox/3.5.9 (.NET CLR 3.5.30729)';

// in order to login successfully we have to provide a redirect url.
// this url has to match the connect url in the app's settings
$next = 'http://matterhorn.web3.all2e.com/ezflow_site_admin/xrowtwitt/callback/';

$cookieFile = 'var/log/fb.cookie.txt';

$fb->setCallBackUrl( $next );

// generate login url via fb api kit
$fbLoginUrl = $fb->getLoginUrl(array('req_perms' => 'email,sms,read_stream,publish_stream'));

echo $fbLoginUrl;

// access to facebook home page (to get the cookies)
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL,             $fbLoginUrl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
curl_setopt($curl, CURLOPT_ENCODING,            "");
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData   = curl_exec($curl);
$curlInfo   = curl_getinfo($curl);

curl_close($curl);

file_put_contents( 'var/log/fb.curl1.txt', $curlData );

// parse login url from fb login form
preg_match('/<form .+?action=\"(.+?)\".+?>/i', $curlData, $loginUrl);
$fbLoginUrl = $loginUrl[1];

// now parse all params from fb login form, especially a param called "lsd" (yes, really!)
// which is used as kind of login spam protection key

if ( preg_match('/<input .+?name=\"api_key\".+?value=\"(.*?)\".+?/>/i', $curlData, $apiKey) ){
    $fbLoginParam['api_key']        = $apiKey[1];
}

if ( preg_match('/<input .+?name=\"return_session\".+?value=\"(.*?)\".+?/>/i', $curlData, $returnSession) ){
    $fbLoginParam['return_session'] = $returnSession[1];
}

if ( preg_match('/<input .+?name=\"req_perms\".+?value=\"(.*?)\".+?/>/i', $curlData, $reqPerms) ){
    $fbLoginParam['req_perms']      = $reqPerms[1];
}

if ( preg_match('/<input .+?name=\"legacy_return\".+?value=\"(.*?)\".+?/>/i', $curlData, $legacyReturn) ){
    $fbLoginParam['legacy_return']  = $legacyReturn[1];
}

if ( preg_match('/<input .+?name=\"display\".+?value=\"(.*?)\".+?/>/i', $curlData, $display) ){
    $fbLoginParam['display']            = $display[1];
}

if ( preg_match('/<input .+?name=\"session_key_only\".+?value=\"(.*?)\".+?/>/i', $curlData, $sessionKeyOnly) ){
    $fbLoginParam['session_key_only']   = $sessionKeyOnly[1];
}

if ( preg_match('/<input .+?name=\"trynum\".+?value=\"(.*?)\".+?/>/i', $curlData, $trynum) ){
    $fbLoginParam['trynum']         = $trynum[1];
}

if ( preg_match('/<input .+?name=\"charset_test\".+?value=\"(.*?)\".+?/>/i', $curlData, $charsetTest) ){
    $fbLoginParam['charset_test']       = $charsetTest[1];
}

if ( preg_match('/<input .+?name=\"lsd\".+?value=\"(.+?)\".*?/>/i', $curlData, $lsd) ){
    $fbLoginParam['lsd']            = $lsd[1];
}

$fbLoginParam['email']              = $username;
$fbLoginParam['pass']               = $password;

// if your user agent string contains some english language, use "Login" instead!
$fbLoginParam['login']              = 'Anmelden';

// create POST param string from param array
$tmp = '';
foreach ($fbLoginParam as $k => $v){
    $tmp[] = $k.'='.$v;
}
$fbLoginParams = join('&', $tmp);

// do login to facebook
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL,             $fbLoginUrl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);     // this time, don't follow fb's redirect
curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
curl_setopt($curl, CURLOPT_HEADER,          1);     // output headers above page content
curl_setopt($curl, CURLINFO_HEADER_OUT,     true);      // also get headers via curlinfo (not necessary)
curl_setopt($curl, CURLOPT_POST,                1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,      false);
curl_setopt($curl, CURLOPT_POSTFIELDS,          $fbLoginParams);
curl_setopt($curl, CURLOPT_ENCODING,            "");
curl_setopt($curl, CURLOPT_COOKIEFILE,          $cookieFile );
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData = curl_exec($curl);
$curlInfo = curl_getinfo($curl);

curl_close($curl);

file_put_contents( 'var/log/fb.curl2.txt', $curlData );

// pull auth token out of the returned headers
preg_match( '/auth_token=([a-z0-9]+)/i', $curlData, $m );
$authToken = $m[1];

echo $authToken;
die();

// get session details from fb and feed it to the api client
$fbSession = $fb->getSession($authToken, true);
$fb->set_user($fbSession['uid'], $fbSession['session_key'], $fbSession['expires'], $fbSession['secret']);

// now we're ready to do do all the funny stuff the fb api is designed for:
$result = $fb->api_client->stream_publish( 'hey facebook - gotcha! :p' );

// ideally, this should contain the id of your stream update
print $result;


*/
/*
$username = 'marketingblog@gmx.de';
$password = 'ebeldied';
$browser = $_SERVER['HTTP_USER_AGENT'];


setFacebookStatus( 'Mein neuer Status', $username, $password, true );

//
// change Facebook status with curl
// Thanks to Alste (curl stuff inspired by nexdot.net/blog)
function setFacebookStatus($status, $login_email, $login_pass, $debug=false) {
    //CURL stuff
    //This executes the login procedure
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://login.facebook.com/login.php?m&amp;next=http%3A%2F%2Fm.facebook.com%2Fhome.php');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&pass=' . urlencode($login_pass) . '&login=' . urlencode("Log in"));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "var/log/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "var/log/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //make sure you put a popular web browser here (signature for your web browser can be retrieved with 'echo $_SERVER['HTTP_USER_AGENT'];'
    curl_setopt($ch, CURLOPT_USERAGENT, $browser);
    curl_exec($ch);

    //This executes the status update
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_URL, 'http://m.facebook.com/home.php');
    $page = curl_exec($ch);

    //echo htmlspecialchars($page);

    curl_setopt($ch, CURLOPT_POST, 1);
    //this gets the post_form_id value
    preg_match("/input type=\"hidden\" name=\"post_form_id\" value=\"(.*?)\"/", $page, $form_id);
    preg_match("/input type=\"hidden\" name=\"fb_dtsg\" value=\"(.*?)\"/", $page, $fb_dtsg);
    preg_match("/input type=\"hidden\" name=\"charset_test\" value=\"(.*?)\"/", $page, $charset_test);
    preg_match("/input type=\"submit\" class=\"button\" name=\"update\" value=\"(.*?)\"/", $page, $update);

    //we'll also need the exact name of the form processor page
    //preg_match("/form action=\"(.*?)\"/", $page, $form_num);
    //sometimes doesn't work so we search the correct form action to use
    //since there could be more than one form in the page.
    preg_match_all("#<form([^>]*)>(.*)</form>#Ui", $page, $form_ar);
    for($i=0;$i<count($form_ar[0]);$i++)
        if(stristr($form_ar[0][$i],"post_form_id")) preg_match("/form action=\"(.*?)\"/", $page, $form_num);    

    $strpost = 'post_form_id=' . $form_id[1] . '&status=' . urlencode($status) . '&update=' . urlencode($update[1]) . '&charset_test=' . urlencode($charset_test[1]) . '&fb_dtsg=' . urlencode($fb_dtsg[1]);
    if($debug) {
        echo "Parameters sent: ".$strpost."<hr>";
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $strpost );

    //set url to form processor page
    curl_setopt($ch, CURLOPT_URL, 'http://m.facebook.com' . $form_num[1]);
    curl_exec($ch);

    if ($debug) {
        //show information regarding the request
        print_r(curl_getinfo($ch));
        echo curl_errno($ch) . '-' . curl_error($ch);
        echo "<br><br>Your Facebook status seems to have been updated.";
    }
    //close the connection
    curl_close($ch);
}
*/


//$fb = new Facebook( '171159026275680', '42f69225112655da870da3c2ee6f2aae', true );

$username = 'marketingblog@gmx.de';
$password = 'ebeldied';
$curloptUserAgent = $_SERVER['HTTP_USER_AGENT'];
$callback_uri = eZSys::serverURL() . eZSys::indexDir() . '/xrowtwitt/callback/';
$cookieFile = 'var/log/fb.cookie.txt';
$requestDataFile = 'var/log/fb.curl1_data.txt';
$requestHeaderFile = 'var/log/fb.curl1_header.txt';
$requestDataFile2 = 'var/log/fb.curl2_data.txt';
$requestHeaderFile2 = 'var/log/fb.curl2_header.txt';
$requestDataFile3 = 'var/log/fb.curl3_data.txt';
$requestHeaderFile3 = 'var/log/fb.curl3_header.txt';


/*
$fb->setCallBackUrl( $callback_uri );

// generate login url via fb api kit
$fbLoginUrl = $fb->getLoginUrl(array('req_perms' => 'email,sms,read_stream,publish_stream'));

echo "<pre>";
echo $fbLoginUrl;

// access to facebook home page (to get the cookies)
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL,                 $fbLoginUrl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
curl_setopt($curl, CURLOPT_ENCODING,            "");
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData   = curl_exec($curl);
$curlInfo   = curl_getinfo($curl);

curl_close($curl);

file_put_contents( $requestDataFile, $curlData );
file_put_contents( $requestHeaderFile, $curlInfo );

// parse login url from fb login form
preg_match('/<form .+?action=\"(.+?)\".+?>/i', $curlData, $loginUrl);
$fbLoginUrl = $loginUrl[1];

br();br();
echo $fbLoginUrl;
br();br();
$fbLoginParam = getInputValuesFromFormContent( $curlData, 'login_form' );



br();
var_dump($fbLoginParam);



$fbLoginParam['email']              = $username;
$fbLoginParam['pass']               = $password;

// create POST param string from param array
$tmp = '';
foreach( $fbLoginParam as $k => $v )
{
    $tmp[] = $k . '=' . $v;
}
$fbLoginParams = join( '&', $tmp );


br();
var_dump($fbLoginParams);


// do login to facebook
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL,                 $fbLoginUrl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
curl_setopt($curl, CURLOPT_HEADER,              0);     // output headers above page content
curl_setopt($curl, CURLINFO_HEADER_OUT,         true);      // also get headers via curlinfo (not necessary)
curl_setopt($curl, CURLOPT_POST,                1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,      false);
curl_setopt($curl, CURLOPT_POSTFIELDS,          $fbLoginParams);
curl_setopt($curl, CURLOPT_ENCODING,            "");
curl_setopt($curl, CURLOPT_COOKIEFILE,          $cookieFile );
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData = curl_exec($curl);
$curlInfo = curl_getinfo($curl);

curl_close($curl);

file_put_contents( $requestDataFile2, $curlData );
file_put_contents( $requestHeaderFile2, $curlInfo );
*/

// GET LOGIN SCREEN
$baseURI = 'http://m.facebook.com';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL,                 $fbLoginUrl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
curl_setopt($curl, CURLOPT_HEADER,              0);     // output headers above page content
curl_setopt($curl, CURLINFO_HEADER_OUT,         true);      // also get headers via curlinfo (not necessary)
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,      false);
curl_setopt($curl, CURLOPT_ENCODING,            "");
curl_setopt($curl, CURLOPT_COOKIEFILE,          $cookieFile );
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData = curl_exec($curl);
$curlInfo = curl_getinfo($curl);
file_put_contents( $requestDataFile, $curlData );
file_put_contents( $requestHeaderFile, $curlInfo );
curl_close($curl);








$fbLoginUrl = getFormActionURL( $curlData, 'login_form' );
$fbLoginParams = getInputValuesFromFormContent( $curlData, 'login_form' );
$fbLoginParams['email'] = $username;
$fbLoginParams['pass'] = $password;
$tmp = '';
foreach( $fbLoginParams as $k => $v )
{
    $tmp[] = $k . '=' . $v;
}
$fbLoginParams = join( '&', $tmp );


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
curl_setopt($curl, CURLOPT_COOKIEFILE,          $cookieFile );
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData = curl_exec($curl);
$curlInfo = curl_getinfo($curl);
file_put_contents( $requestDataFile2, $curlData );
file_put_contents( $requestHeaderFile2, $curlInfo );
curl_close($curl);


$fbLoginUrl = 'http://m.facebook.com/home.php';
// do login to facebook
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL,                 $fbLoginUrl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION,      1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,      1);
curl_setopt($curl, CURLOPT_HEADER,              0);     // output headers above page content
curl_setopt($curl, CURLINFO_HEADER_OUT,         true);      // also get headers via curlinfo (not necessary)
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,      false);
curl_setopt($curl, CURLOPT_ENCODING,            "");
curl_setopt($curl, CURLOPT_COOKIEFILE,          $cookieFile );
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData = curl_exec($curl);
$curlInfo = curl_getinfo($curl);

curl_close($curl);

file_put_contents( $requestDataFile3, $curlData );
file_put_contents( $requestHeaderFile3, $curlInfo );



// parse login url from fb login form


$fbLoginUrl = getFormActionURL( $curlData, 'composer_form' );
$fbLoginUrl = 'http://m.facebook.com' . $fbLoginUrl;
$fbPostParams = getInputValuesFromFormContent( $curlData, 'composer_form' );
$fbPostParams['status'] = "Ich Log mich per Handy-Web ein!";

var_dump($fbLoginUrl);
br();
var_dump($fbPostParams);

// create POST param string from param array
$tmp = '';
foreach( $fbPostParams as $k => $v )
{
    $tmp[] = $k . '=' . $v;
}
$fbPostParams = join( '&', $tmp );

br();
var_dump($fbPostParams);

// do login to facebook
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
curl_setopt($curl, CURLOPT_COOKIEFILE,          $cookieFile );
curl_setopt($curl, CURLOPT_COOKIEJAR,           $cookieFile );
curl_setopt($curl, CURLOPT_USERAGENT,           $curloptUserAgent);

$curlData = curl_exec($curl);
$curlInfo = curl_getinfo($curl);

file_put_contents( $requestDataFile3, $curlData );
file_put_contents( $requestHeaderFile3, $curlInfo );

curl_close($curl);

die();


function getInputValuesFromFormContent( $contents, $form_id )
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
    return $inputParams;
}

function getFormActionURL( $contents, $form_id )
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

function br()
{
    echo "<br />";
}
?>