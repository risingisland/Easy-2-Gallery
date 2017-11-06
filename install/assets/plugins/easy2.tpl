/**
 * easy2
 *
 * Easy 2 Gallery Plugin
 *
 * @category	plugins
 * @version     1.6-rc1
 * @author      Author: @goldsky @Breezer @risingisland @Nicola1971
 * @internal	@modx_category Gallery
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal @events OnWebPageInit,OnWebPageComplete
 * @internal @installset base
 * @internal @properties {}
 */

$pluginFile = realpath(MODX_BASE_PATH . 'assets/modules/easy2/plugin.easy2gallery.php');
if (!empty($pluginFile) && file_exists($pluginFile)) {
    return include $pluginFile;
} else {
    return '';
}
