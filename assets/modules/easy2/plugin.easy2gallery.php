<?php
defined( 'MGR_DIR' ) or die( '<b>UNAUTHORIZED_ACCESS_ERROR</b>' );
/**
 * EASY 2 GALLERY
 * Plugin for Easy 2 Gallery Module for MODx Evolution
 * @version 1.5.0-rc2
 */
 
// page id for the easy2gallery comments / ss viewer page link
$e2g_vpl_id = 48;


global $e;
$e = &$modx->Event;

switch ($e->name) {

    case 'OnWebPageInit':
        
        // easy2gallery viewer page
        // &p=com (comments)  /  &p=show  (all others)
        // used in:
        // comments_processor.easy2gallery.php
        // e2g.snippet.class.php
        
        // we need to set this every page change in case the doc id is changed or furls are turned on/off
        $_SESSION['e2g_view_processor_link'] =$modx->makeUrl( (int)$e2g_vpl_id ) . ( $modx->config['friendly_urls'] =='0' ? '&' : '?' );

        //$modx->runSnippet( 'pretty_array' );
        
        $_SESSION['e2g_instances'] = 1;

        break;
        
    case 'OnWebPageComplete':
        unset($_SESSION['e2g_instances']);
        break;
        
    case 'OnDocFormRender':
        /**
         * need a patch in the mutate_content.dynamic.php, line 1166
         * @link http://modxcms.com/forums/index.php/topic,52295.msg303089.html#msg303089
         */
/*
        <div class="tab-page" id="tabEasy2Gallery">
            <h2 class="tab">Easy 2 Gallery</h2>
            <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabEasy2Gallery" ) );</script>
            <div>

        //ob_start();
        //include MODX_BASE_PATH . 'assets/modules/easy2/index.php';
        //$buffer = ob_get_contents();
        //ob_end_clean();
        //return $buffer;

    </div>
</div>
*/
        break;
        
    default :
        break;
}

return;
