<?php
/**
 * Main include file for the Embed Page extension of MediaWiki.
 * This code is released under the GNU General Public License.
 *
 *
 * Purpose:
 *     creates an embed option for wiki pages works with the https://github.com/ubc/EmbedTracker  extension
 *
 * Usage:
 *     require_once("extensions/EmbedPage/EmbedPage.php"); in LocalSettings.php
 *
 * @package MediaWiki
 * @subpackage Extensions
 * @author Scott McMillan (email: user "scott.mcmillan" at ubc.ca)  UBC Centre for Teaching, Learning and Technology
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */

if( !defined( 'MEDIAWIKI' ) ) {
        echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
        die( 1 );
}

$wgExtensionCredits['parserhook'][] = array(
        'path' => __FILE__,
        'name' => 'EmbedPage',
        'version' => '1.01',
        'description' => 'Creates an embed syndication option for a wiki article.',
        'author' => array('Scott McMillan'),
);

$wgHooks['SkinTemplateToolboxEnd'][] = 'wfEmbedPageToolboxLink';

/**
 * Adds the Wiki feed links to the bottom of the toolbox in Monobook or like-minded skins.
 * Usage: $wgHooks['SkinTemplateToolboxEnd'][] = 'wfEmbedPageToolboxLink';
 * @param QuickTemplate $template Instance of MonoBookTemplate or other QuickTemplate
 */

function wfEmbedPageToolboxLink($template) {

    global $wgServer, $wgScript;

    $pageSkin = $template->getSkin();
    if (! $pageSkin) {
        return true;
    }
    $pageTitle = $pageSkin->getTitle();
    if (! $pageTitle) {
        return true;
    }
    $dbKey = $pageTitle->getPrefixedDBkey();

    $embedAction = "document.getElementById('article_embed').style.display = (document.getElementById('article_embed').style.display != 'none' ? 'none' : '' ); return false";

    // urlencode $dbKey twice to handle special characters
    $embedPageCode = "<script type=\"text/javascript\"> document.write('<script type=\"text/javascript\" charset=\"utf-8\" src=\"$wgServer/extensions/EmbedPage/getPage.php?title=$wgScript/" . urlencode(urlencode($dbKey)) . "&referer=' + document.location.href + ' \"><\/script>');</script>";
    $embedPageCode1 = "<iframe style=\"height: 100vh; width: 100%; border: 0 none;\" src=\"$wgServer/$wgScript/" . urlencode(urlencode($dbKey)) . "?action=render\"></iframe>";

    echo "<li><a href='#' onclick=\"$embedAction\">Embed Page</a></li>";

    echo '<div id="article_embed" style="display:none;">
        <span style="font-size:10px;">Canvas:</span><textarea style="margin:0; width:95%;font-size:10px; height:120px;" onClick="this.select();">' . $embedPageCode1 . '</textarea><br/>
        <span style="font-size:10px;">Others:</span><textarea style="margin:0; width:95%;font-size:10px; height:120px;" onClick="this.select();">' . $embedPageCode . '</textarea>
         </div>';

    return true;
}
?>
