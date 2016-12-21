<?php
defined( 'E2G_MODE' ) or die( '<b>UNAUTHORIZED_ACCESS_ERROR</b>' );
/**
 * EASY 2 GALLERY
 * Comments processor for Easy 2 Gallery Module for MODx Evolution
 * @author breezer <breezerfrazer@frsbuilders.com>
 * @package     easy 2 gallery
 * @subpackage  easy 2 comments processor
 * @version 1.5.0-rc2
 */


// do we want to use the captcha included with Evolution
// templates/default/captcha.tpl
$e2g['captcha'] = isset( $captcha ) && $captcha =='1' ? true : false;
$e2g['captcha_tpl'] = isset( $captcha_tpl ) ? $modx->getChunk( $captcha_tpl ) : '';
$e2g['comments_lang'] = isset( $comments_lang ) && $comments_lang !='' ? $comments_lang : 'english';
$e2g['auto_approved'] = isset( $approved ) && $approved =='1' ? true : false;
$e2g['comments_delay'] = isset( $delay ) ? (int)$delay : 90;


// get the e2g config settings
$e2g_settings = $modx->db->makeArray( $modx->db->select( "*", $modx->getFullTableName( 'easy2_configs' ), '', '', '' ) );

if( !$e2g_settings ){

    $modx->logEvent( 0, 3, __LINE__ ." - query error for e2g configs", $modx->getSnippetName() );
    return;

}

foreach( $e2g_settings as $k => $v ){

    $e2g[$v['cfg_key']] = $v['cfg_val'];

}


// check whether comments are enabled
// snippet call settings override config settings
$ecm = isset( $ecm ) ? $ecm : 0;

if ( $e2g['ecm'] == 0 && $ecm !='1' ) {

    echo "comments disabled";
    return;
    
}


// decide whether we are on the gallery landing page
if( isset( $_GET['fid'] )){

    // landing page settings
    $landing =true;
    $rid = (int)$_GET['fid'];
    $com_limit =isset( $ecl_page ) ? $ecl_page : $e2g['ecl_page'];
    
    $comments_page_link =$modx->makeUrl( $modx->documentObject['id'] ) . ( $modx->config['friendly_urls'] =='0' ? '&' : '?' );
    $comments_page_link .= 'lp=' . $modx->documentObject['id'] . '&fid=' . $rid;
    $cpl_xtra ='#lpcmtpg[+com_page_num+]';
    
    $comments_row_tpl ='page_comments_row_tpl';
    $comments_tpl ='page_comments_tpl';
    
    $modx->regClientCSS( $e2g['page_tpl_css'] );

}else{

// we are viewing the comments via iframe
    if( empty( $_GET['rid'] ) || !is_numeric( $_GET['rid'] )) {

        $modx->logEvent( 0, 3, __LINE__ ." GET['rid'] Error!", $modx->getSnippetName() );
        die;
    
    }else{
    
        // viewer page settings
        $rid = (int)$_GET['rid'];
        $com_limit =isset( $ecl ) ? $ecl : $e2g['ecl'];

        $comments_page_link =$_SESSION['e2g_view_processor_link'] ."rid=" . $rid . "&p=com";
        $cpl_xtra ='';
        
        $comments_row_tpl ='comments_row_tpl';
        $comments_tpl ='comments_tpl';

        $landing =false;
    }
}


// comments page number
$cpn = ( empty( $_GET['cpn'] ) || !is_numeric( $_GET['cpn'] )) ? 0 : (int) $_GET['cpn'];


// comments language file
$lang = $e2g_path.'includes/langs/'.$e2g['comments_lang'].'.comments.php';
if ( file_exists( $lang )){

    include $lang;
    $lng = $e2g_lang[$e2g['comments_lang']];
    
}else{

    $lng = $e2g_lang['english'];
    
}


// comments charset
$_P['charset'] = $modx->config['modx_charset'];


// output from language file
$_P['title'] = $lng['title'];
$_P['comment_add'] = $lng['comment_add'];
$_P['name'] = $lng['name'];
$_P['email'] = $lng['email'];
$_P['usercomment'] = $lng['usercomment'];
$_P['send_btn'] = $lng['send_btn'];
$_P['comment_body'] = '';
$_P['comment_pages'] = '';
$_P['code'] = $lng['code'];
$_P['waitforapproval'] = $lng['waitforapproval'];

$continue =true;

// INSERT THE COMMENT INTO DATABASE
if( !empty( $_POST['name'] ) && !empty( $_POST['comment'] )) {

    // check for comments delay
    if( isset( $e2g['comments_delay'] )){
    
        if( (int)$_SESSION['last_post'] + $e2g['comments_delay'] >= $_SERVER['REQUEST_TIME'] ){
        
            $_P['comment_body'] .= '<h2 style="color:red;">' . $lng['comments_delay'] . '</h2>';

            $continue =false;
        }
    }
    
    // check email
    $e = htmlspecialchars( trim( $_POST['email'] ), ENT_QUOTES);

    if( checkEmailAddress( $e ) == FALSE ) {
    
        $_P['comment_body'] .= '<h2 style="color:red;">' . $lng['email_err'] . '</h2>';
        
        $continue =false;
        
    }
    
    // check reCatcha blank field
    if ( $e2g['recaptcha'] == 1 && ( trim( $_POST['recaptcha_response_field']) == '' )) {
    
        $_P['comment_body'] .= '<h2 style="color:red;">' . $lng['recaptcha_err'] . '</h2>';
        
        $continue =false;
        
    }


    if( $continue ){

        // count active comments
        $com_count = $modx->db->getValue( $modx->db->select( 'COUNT(*)', $modx->getFullTableName( 'easy2_comments' ), 'file_id = ' . $rid , '', '' ) );

        $comment = htmlspecialchars( trim( $_POST['comment'] ), ENT_QUOTES);
        $comment = $modx->db->escape( $comment );
        // set up the database fields
        $fields = array(
                'file_id'     => $rid,
                'author'      => htmlspecialchars( trim( $_POST['name'] ), ENT_QUOTES),
                'email'       => $e,
                'ip_address'  => isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
                'comment'     => $comment,
                'date_added'  => date( "Y-m-d H:i:s" ),  //2007-12-15 23:50:26
                'approved'    => ( $e2g['auto_approved'] ? 1 : 0 ),
                'status'      => ( $e2g['auto_approved'] ? 1 : 0 )
                );
                
        //reCaptcha
        if($e2g['recaptcha'] == 1 && $_POST['recaptcha_response_field']) {
            require_once $e2g_path.'includes/recaptchalib.php';
            # the response from reCAPTCHA
            $resp = null;
            # the error code from reCAPTCHA, if any
            $error = null;

            # was there a reCAPTCHA response?
            if($_POST["recaptcha_response_field"]) {
                $privatekey = $e2g['recaptcha_key_private'];
                $resp = recaptcha_check_answer($privatekey,
                            $_SERVER["REMOTE_ADDR"],
                            $_POST["recaptcha_challenge_field"],
                            $_POST["recaptcha_response_field"]);

                if (!$resp->is_valid) {
                    # set the error code so that we can display it
                    $error = $resp->error;
                } else {

                    // insert the comment
                    $insert = $modx->db->insert( $fields, $modx->getFullTableName( 'easy2_comments' ));

                    if( $insert ){

                        $fields2 = array(
                                    'comments' => intval( $com_count ) + 1
                                    );

                        $update = $modx->db->update( $fields2, $modx->getFullTableName( 'easy2_files' ), 'id='.$rid );

                        $_P['comment_body'] .= '<h3>' . $lng['comment_added'] . '</h3>';
                    
                        $_SESSION['last_post'] = $_SERVER['REQUEST_TIME'];
                        
                    }else{
                
                        $_P['comment_body'] .= '<h2 style="color:red;">' . $lng['comment_add_err'] . '</h2>';
                    
                    }
                }
            }
        }else{
    
            // captcha
            // verify form code
            if( $e2g['captcha'] && $_SESSION['veriword'] != $_POST['formcode'] ) {

                $_P['comment_body'] .= '<h2 style="color:red;">'. $lng['captcha_vericode_err'] .'</h2>';
                
            }else{

                // insert the comment
                $insert = $modx->db->insert( $fields, $modx->getFullTableName( 'easy2_comments' ));

                if( $insert ){

                    $fields2 = array(
                            'comments' => intval( $com_count ) + 1
                            );

                    $update = $modx->db->update( $fields2, $modx->getFullTableName( 'easy2_files' ), 'id='.$rid );

                    $_P['comment_body'] .= '<h3>' . $lng['comment_added'] . '</h3>';
            
                    $_SESSION['last_post'] = $_SERVER['REQUEST_TIME'];
                    
                }else {
        
                    $_P['comment_body'] .= '<h2 style="color:red;">' . $lng['comment_add_err'] . '</h2>';
            
                }
            }
        }
    }
}else{

    if ($_POST ){

        if( empty($_POST['name'])){

            $_P['comment_body'] .= '<h2 style="color:red;">' . $lng['comment_empty_name'] . '</h2>';
            
        }

        if( empty($_POST['comment'])){

            $_P['comment_body'] .= '<h2 style="color:red;">' . $lng['comment_empty_comment'] . '</h2>';

        }
    }
}


// COMMENT ROW TEMPLATE
if (file_exists(MODX_BASE_PATH.$e2g[$comments_row_tpl])) {

    $row_tpl = file_get_contents(MODX_BASE_PATH.$e2g[$comments_row_tpl]);

} elseif (!($row_tpl = getChunk($e2g[$comments_row_tpl]))) {

    $modx->logEvent( 0, 3, __LINE__ ."comments row template not found", $modx->getSnippetName() );
    die('Comments row template not found!');
}

$res = $modx->db->makeArray(
                $modx->db->select( '*', $modx->getFullTableName( 'easy2_comments' ),
                                'file_id ='.$rid.' AND status=1 AND approved=1 ', 'id DESC', ($cpn * $com_limit) . ', ' . $com_limit ) );

$count = count( $res );

if( $res ){

    for( $i=0; $i<$count; $i++ ) {

        $res[$i]['i'] = $i % 2;

        $res[$i]['name_permalink'] = '<a href="#" name="lpcmtpg' . $res[$i]['id'] . '"></a> ';

        $res[$i]['name_w_permalink'] = '<a href="'.$_SERVER['REQUEST_URI'].'#lpcmtpg' . $cpn . '" name="lpcmtpg' . $cpn . '">' . $res[$i]['author'] . '</a>';

        $res[$i]['name_w_mail'] = '<a href="mailto:' . $res[$i]['email'] . '">' . $res[$i]['author'] . '</a>';

        $res[$i]['comment'] = nl2br( $res[$i]['comment'] );
        
        $_P['comment_body'] .= filler($row_tpl, $res[$i]);
        
    }
}


// COUNT PAGES
$cnt = $modx->db->getValue( $modx->db->select( 'COUNT(*)', $modx->getFullTableName( 'easy2_comments' ), 'file_id = ' . $rid.' AND STATUS=1' , '', '' ) );

if ($cnt > $com_limit) {

    $_P['comment_pages'] = '<p class="pnums">' . $lng['pages'] . ':';

    $i = 0;

    while ($i * $com_limit < $cnt) {

        if ($i == $cpn)

            $_P['comment_pages'] .= '<b>' . ($i + 1) . '</b> ';

        else

            $_P['comment_pages'] .= '<a href="'. $comments_page_link . '&cpn='. $i .''.( $cpl_xtra !='' ? str_replace( '[+com_page_num+]', $i, $cpl_xtra ) : '' ).'">' . ($i + 1) . '</a> ';

        $i++;

    }
    
    $_P['comment_pages'] .= '</p>';
}


// COMMENT TEMPLATE

if (file_exists( MODX_BASE_PATH.$e2g[$comments_tpl] )) {

    $tpl = file_get_contents(MODX_BASE_PATH.$e2g[$comments_tpl]);

} elseif (!($tpl = getChunk($e2g[$comments_tpl]))) {

    $modx->logEvent( 0, 3, "comments template not found", $modx->getSnippetName() );
    die('Comments template not found!');
}


// auto complete logged in web users name / email in add comment form
//  [+WEBUSER_NAME+]   [+WEBUSER_EMAIL+]
if( isset( $_SESSION['webShortname'] )) {
    
    $replace = array(
                '[+WEBUSER_NAME+]'   => ' value="'.$_SESSION['webShortname'].'"',
                '[+WEBUSER_EMAIL+]'  => ' value="'.$_SESSION['webEmail'].'"'
                );
    $tpl = str_replace( array_keys( $replace ), array_values( $replace ), $tpl );

}

// keep the form fields in case of an omitted field
// if a comment is inserted, the form is cleared
if( isset( $_POST ) && !$insert ){

    // set placeholders for $_POST if form is not filled correctly
    // [+WEBUSER_NAME+]   [+WEBUSER_EMAIL+]  [+WEBUSER_COMMENT+]
    $n = htmlspecialchars(trim($_POST['name']), ENT_QUOTES);
    $c = htmlspecialchars(trim($_POST['comment']), ENT_QUOTES);
    $e = htmlspecialchars(trim($_POST['email']), ENT_QUOTES);

    $replace = array(
                '[+WEBUSER_NAME+]'     => ' value="'.$n.'"',
                '[+WEBUSER_EMAIL+]'    => ' value="'.$e.'"',
                '[+WEBUSER_COMMENT+]'  => $c
                );
    $tpl = str_replace( array_keys( $replace ), array_values( $replace ), $tpl );
}


// form captchas
// populates [+easy2:recaptcha+] section

// first check if we are using reCaptcha
$_P['recaptcha'] = '';
if ($e2g['recaptcha'] == 1) {

    $publickey = $e2g['recaptcha_key_public'];

    $_P['recaptcha'] = '
                <tr>
                    <td colspan="4">' . recaptchaForm($e2g, $publickey, $error) . '</td>
                </tr>';

}

// check if we are using the built in captcha vericode
// &captcha=`0|1`
if ( $e2g['captcha'] && $_P['recaptcha'] =='' ){

    if( $e2g['captcha_tpl'] ==''){

        if (file_exists( MODX_BASE_PATH.'assets/modules/easy2/templates/default/captcha.tpl' )) {

            $e2g['captcha_tpl'] = file_get_contents( MODX_BASE_PATH.'assets/modules/easy2/templates/default/captcha.tpl' );

        }else{

            $modx->logEvent( 0, 3, "captcha template not found", $modx->getSnippetName() );
            die('captcha template not found!');
        }
    }

    $replace = array(
                '[+action+]'  => $_SERVER['REQUEST_URI'],
                '[+rand+]'    => rand()
                );
                
    $_P['recaptcha'] = str_replace( array_keys( $replace ), array_values( $replace ), $e2g['captcha_tpl'] );

}


$_P['pages_permalink'] = '<a href="#" name="lpcmtpg' . $cpn . '"></a>';


// OUTPUT
// if this is not the landing page, send the output to the view processor snippet
if( !$landing ){

    $output =filler( $tpl, $_P );

}else{

    // Landing page
    // use this method so that the comments are enclosed in the e2g wrapper
    // on the landing page - note the changed name of the placeholder
    // [+easy2_comments+] - landingpage.tpl
    
    $modx->setPlaceholder( 'easy2_comments', filler( $tpl, $_P ));

    //$modx->setPlaceholder( 'easy2_comments', filler( $tpl, $_P ) . '<p>'.pretty_array( $_POST ).'</p>' );

}



/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 * @param string $pubkey A public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA (optional, default is null)
 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)

 * @return string - The HTML to be embedded on the user's form.
 */
function recaptchaForm($e2g, $pubkey, $error = null, $use_ssl = false) {
    require_once $e2g_path.'includes/recaptchalib.php';

    $theme = $e2g['recaptcha_theme'];
    $theme_custom = $e2g['recaptcha_theme_custom'];

    if ($pubkey == null || $pubkey == '') {
        die("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create' target=\"_blank\">https://www.google.com/recaptcha/admin/create</a>");
    }

    if ($use_ssl) {
        $server = RECAPTCHA_API_SECURE_SERVER;
    } else {
        $server = RECAPTCHA_API_SERVER;
    }

    $errorpart = "";
    if ($error) {
        $errorpart = "&amp;error=" . $error;
    }
    return '
        <script type="text/javascript">
        var RecaptchaOptions = {
        theme : \'' . $theme . '\'
            ' . ($theme == 'custom' ? ',custom_theme_widget: \'' . $theme_custom . '\'' : '') . '};
        </script>
        <script type="text/javascript" src="' . $server . '/challenge?k=' . $pubkey . $errorpart . '"></script>
        <noscript>
            <iframe src="' . $server . '/noscript?k=' . $pubkey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
            <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
            <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
        </noscript>';
}

function filler($tpl, $data, $prefix = '[+easy2:', $suffix = '+]') {
    foreach ($data as $k => $v) {
        $tpl = str_replace($prefix . (string) $k . $suffix, (string) $v, $tpl);
    }
    return $tpl;
}

function getChunk($name) {
    global $modx;
    $chunk_content = $modx->db->getValue( $modx->db->select( "snippet", $modx->getFullTableName( 'site_htmlsnippets' ), 'name="'.$modx->db->escape($name).'"' , "", "1" ) );

    if( $chunk_content !='' ){
        return $chunk_content;
    }else{
        return false;
    }
}

function checkEmailAddress($email) {
    if (!preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email)) {
        return false;
    }
    return true;
}

?>
