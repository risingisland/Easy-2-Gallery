/**
 * easy2
 *
 * Easy 2 Gallery Snippet
 *
 * @category	snippet
 * @version     1.6-rc1
 * @author      Author: @goldsky @Breezer @risingisland @Nicola1971
 * @internal	@modx_category Gallery
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 */

$snippetFile = realpath(MODX_BASE_PATH . 'assets/modules/easy2/snippet.easy2gallery.php');
if (!empty($snippetFile) && file_exists($snippetFile)) {
    return include $snippetFile;
} else {
    return '';
}