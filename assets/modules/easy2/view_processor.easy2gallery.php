<?php
/**
 * EASY 2 GALLERY
 * View processor for Easy 2 Gallery Module for MODx Evolution
 * @author breezer <breezerfrazer@frsbuilders.com>
 * @package     easy 2 gallery
 * @subpackage  easy 2 view processor
 * @version 1.5.0-rc2
 */
defined( 'E2G_MODE' ) or die( '<b>UNAUTHORIZED_ACCESS_ERROR</b>' );

$p = isset( $_GET['p'] ) ? $_GET['p'] : '';

$viewer_page ='';

switch( $p ){

    case 'com':
        $viewer_page ='comments';
        break;

    case 'show':
        $viewer_page ='show';
        break;
        
    default : '';
        break;
}


if( $viewer_page =='comments' || $viewer_page =='show' ){

    include $e2g_path.$viewer_page.'_processor.easy2gallery.php';
    
}else{

    $output ='<p>NO ACCESS ALLOWED</p>';
}

?>
